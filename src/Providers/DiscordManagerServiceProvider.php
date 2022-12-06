<?php

namespace Revolution\DiscordManager\Providers;

use Discord\Discord as DiscordPHP;
use Discord\WebSockets\Event;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use RestCord\DiscordClient;
use Revolution\DiscordManager\Console;
use Revolution\DiscordManager\Contracts\Factory;
use Revolution\DiscordManager\Contracts\InteractionsEvent;
use Revolution\DiscordManager\Contracts\InteractionsResponse;
use Revolution\DiscordManager\DiscordManager;
use Revolution\DiscordManager\Events\InteractionsWebhook;
use Revolution\DiscordManager\Http\Controllers\InteractionsWebhookController;
use Revolution\DiscordManager\Http\Middleware\ValidateSignature;
use Revolution\DiscordManager\Http\Response\ChannelMessageResponse;
use Revolution\DiscordManager\Http\Response\DeferredResponse;
use Revolution\DiscordManager\Http\Response\PongResponse;
use Revolution\DiscordManager\Support\Intents;

class DiscordManagerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/discord_interactions.php',
            'discord_interactions'
        );

        $this->app->singleton(Factory::class, function ($app) {
            return new DiscordManager(
                config('services.discord')
            );
        });

        $this->app->singleton(DiscordClient::class, function ($app) {
            return new DiscordClient([
                'token' => config('services.discord.token'),
            ]);
        });

        $this->app->singleton(DiscordPHP::class, function ($app) {
            return new DiscordPHP(array_merge([
                'token' => config('services.discord.token'),
            ], config('services.discord.discord-php', [
                'disabledEvents' => [
                    Event::TYPING_START,
                ],
                'intents' => array_sum(Intents::default()),
            ])));
        });

        $this->app->singleton(InteractionsResponse::class, ChannelMessageResponse::class);
        $this->app->singleton(InteractionsEvent::class, InteractionsWebhook::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\MakeCommand::class,
                Console\MakeDirect::class,
                Console\RegisterCommand::class,
            ]);
        }

        Http::macro('discord', fn (int $api_version = 10): PendingRequest => Http::withHeaders([
            'Authorization' => 'Bot '.config('services.discord.token')
        ])->baseUrl('https://discord.com/api/v'.$api_version));

        $this->configurePublishing();

        $this->interactions();
    }

    protected function interactions()
    {
        Route::middleware(config('services.discord.interactions.middleware', 'throttle'))
             ->domain(config('services.discord.interactions.domain'))
             ->group(function () {
                 Route::post(config('services.discord.interactions.path', 'discord/webhook'))
                      ->name(config('services.discord.interactions.route', 'discord.webhook'))
                      ->middleware(ValidateSignature::class)
                      ->uses(InteractionsWebhookController::class);
             });
    }

    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        if (! $this->app->runningInConsole()) {
            return; // @codeCoverageIgnore
        }

        $this->publishes([
            __DIR__.'/../../config/discord_interactions.php' => $this->app->configPath('discord_interactions.php'),
        ], 'discord-interactions-config');
    }
}
