<?php

declare(strict_types=1);

namespace Revolution\DiscordManager\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Revolution\DiscordManager\Console;
use Revolution\DiscordManager\Contracts\Factory;
use Revolution\DiscordManager\Contracts\InteractionsEvent;
use Revolution\DiscordManager\Contracts\InteractionsResponse;
use Revolution\DiscordManager\DiscordCommandRegistry;
use Revolution\DiscordManager\Events\InteractionsWebhook;
use Revolution\DiscordManager\Http\Controllers\InteractionsWebhookController;
use Revolution\DiscordManager\Http\Middleware\DispatchInteractionsEvent;
use Revolution\DiscordManager\Http\Middleware\ValidateSignature;
use Revolution\DiscordManager\Http\Response\DeferredResponse;

class DiscordInteractionsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/discord_interactions.php',
            'discord_interactions'
        );

        $this->app->singleton(Factory::class, DiscordCommandRegistry::class);

        $this->app->singleton(InteractionsResponse::class, DeferredResponse::class);
        $this->app->singleton(InteractionsEvent::class, InteractionsWebhook::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\MakeInteraction::class,
                Console\RegisterCommand::class,
            ]);
        }

        $this->interactionsRoute();

        $this->configurePublishing();
    }

    protected function interactionsRoute(): void
    {
        if (config('discord_interactions.ignore_route') === true) {
            return; // @codeCoverageIgnore
        }

        Route::middleware(config('discord_interactions.middleware', 'throttle'))
            ->domain(config('discord_interactions.domain'))
            ->group(function () {
                Route::post(config('discord_interactions.path', 'discord/webhook'))
                    ->name(config('discord_interactions.route', 'discord.webhook'))
                    ->middleware([
                        ValidateSignature::class,
                        DispatchInteractionsEvent::class,
                    ])
                    ->uses(InteractionsWebhookController::class);
            });
    }

    /**
     * Configure publishing for the package.
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
