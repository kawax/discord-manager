<?php

namespace Revolution\DiscordManager\Providers;

use Discord\Discord as DiscordPHP;
use Discord\WebSockets\Event;
use Illuminate\Support\ServiceProvider;
use RestCord\DiscordClient as RestCord;
use Revolution\DiscordManager\Console;
use Revolution\DiscordManager\Contracts\Factory;
use Revolution\DiscordManager\DiscordManager;
use Revolution\DiscordManager\Support\Intents;

class DiscordManagerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Factory::class, function ($app) {
            return new DiscordManager(
                config('services.discord')
            );
        });

        if (class_exists(RestCord::class)) {
            $this->app->singleton(RestCord::class, function ($app) {
                return new RestCord([
                    'token' => config('services.discord.token'),
                ]);
            });
        }

        // @codeCoverageIgnoreStart
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
        // @codeCoverageIgnoreEnd
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
                Console\MakeCommand::class,
                Console\MakeDirect::class,
            ]);
        }
    }
}
