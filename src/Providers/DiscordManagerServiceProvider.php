<?php

namespace Revolution\DiscordManager\Providers;

use Discord\Discord as DiscordPHP;
use Discord\WebSockets\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use RestCord\DiscordClient;
use Revolution\DiscordManager\Console;
use Revolution\DiscordManager\Contracts\Factory;
use Revolution\DiscordManager\DiscordManager;
use Revolution\DiscordManager\Http\Controllers\InteractionsWebhookController;
use Revolution\DiscordManager\Http\Middleware\ValidateSignature;
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
            ]);
        }

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
}
