<?php

namespace Revolution\DiscordManager\Providers;

use CharlotteDunois\Yasmin\Client as Yasmin;
use Discord\Discord as DiscordPHP;
use Discord\WebSockets\Event;
use Illuminate\Support\ServiceProvider;
use React\EventLoop\Factory as React;
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
        $this->app->singleton(Factory::class, function () {
            return new DiscordManager(
                $this->app['config']->get('services.discord')
            );
        });

        $this->app->singleton(DiscordClient::class, function () {
            return new DiscordClient([
                'token' => $this->app['config']->get('services.discord.token'),
            ]);
        });

        $this->app->singleton(Yasmin::class, function () {
            return new Yasmin(
                $this->app['config']->get('services.discord.yasmin', [
                    'ws.disabledEvents' => [
                        'TYPING_START',
                    ],
                    'intents'           => array_sum(Intents::default()),
                ]),
                React::create()
            );
        });

        $this->app->singleton(DiscordPHP::class, function () {
            return new DiscordPHP(array_merge([
                'token' => $this->app['config']->get('services.discord.token'),
            ], $this->app['config']->get('services.discord.discord-php', [
                'disabledEvents' => [
                    Event::TYPING_START,
                ],
                'intents'        => array_sum(Intents::default()),
            ])));
        });
    }
}
