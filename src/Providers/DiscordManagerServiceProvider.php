<?php

namespace Revolution\DiscordManager\Providers;

use Discord\Discord as DiscordPHP;
use Discord\WebSockets\Event;
use Illuminate\Support\ServiceProvider;
use RestCord\DiscordClient;
use Revolution\DiscordManager\Console;
use Revolution\DiscordManager\Contracts\Factory;
use Revolution\DiscordManager\DiscordManager;
use Revolution\DiscordManager\Support\Intents;

class DiscordManagerServiceProvider extends ServiceProvider
{
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
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Factory::class, function ($app) {
            return new DiscordManager(
                $app['config']->get('services.discord')
            );
        });

        $this->app->singleton(DiscordClient::class, function ($app) {
            return new DiscordClient([
                'token' => $app['config']->get('services.discord.token'),
            ]);
        });

        $this->app->singleton(DiscordPHP::class, function ($app) {
            return new DiscordPHP(array_merge([
                'token' => $app['config']->get('services.discord.token'),
            ], $app['config']->get('services.discord.discord-php', [
                'disabledEvents' => [
                    Event::TYPING_START,
                ],
                'intents'        => array_sum(Intents::default()),
            ])));
        });
    }
}
