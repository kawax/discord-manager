<?php

namespace Revolution\DiscordManager\Providers;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Revolution\DiscordManager\Console;
use Revolution\DiscordManager\Contracts\InteractionsEvent;
use Revolution\DiscordManager\Contracts\InteractionsResponse;
use Revolution\DiscordManager\Events\InteractionsWebhook;
use Revolution\DiscordManager\Http\Controllers\InteractionsWebhookController;
use Revolution\DiscordManager\Http\Middleware\DispatchInteractionsEvent;
use Revolution\DiscordManager\Http\Middleware\ValidateSignature;
use Revolution\DiscordManager\Http\Response\ChannelMessageResponse;
use Revolution\DiscordManager\Http\Response\DeferredResponse;
use Revolution\DiscordManager\Http\Response\PongResponse;

class DiscordInteractionsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/discord_interactions.php',
            'discord_interactions'
        );

        $this->app->singleton(InteractionsResponse::class, DeferredResponse::class);
        $this->app->singleton(InteractionsEvent::class, InteractionsWebhook::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\MakeInteraction::class,
                Console\RegisterCommand::class,
            ]);
        }

        Http::macro('discord', fn (int $version = 10): PendingRequest => Http::withHeaders([
            'Authorization' => 'Bot '.config('services.discord.token'),
        ])->baseUrl('https://discord.com/api/v'.$version));

        $this->interactionsRoute();

        $this->configurePublishing();
    }

    protected function interactionsRoute(): void
    {
        if (config('services.discord.interactions.ignore_route') === true) {
            return; // @codeCoverageIgnore
        }

        Route::middleware(config('services.discord.interactions.middleware', 'throttle'))
             ->domain(config('services.discord.interactions.domain'))
             ->group(function () {
                 Route::post(config('services.discord.interactions.path', 'discord/webhook'))
                      ->name(config('services.discord.interactions.route', 'discord.webhook'))
                      ->middleware([
                          ValidateSignature::class,
                          DispatchInteractionsEvent::class,
                      ])
                      ->uses(InteractionsWebhookController::class);
             });
    }

    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    protected function configurePublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return; // @codeCoverageIgnore
        }

        $this->publishes([
            __DIR__.'/../../config/discord_interactions.php' => $this->app->configPath('discord_interactions.php'),
        ], 'discord-interactions-config');
    }
}
