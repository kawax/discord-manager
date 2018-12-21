<?php

namespace Revolution\DiscordManager\Providers;

use Illuminate\Support\ServiceProvider;

use Revolution\DiscordManager\Contracts\Factory;
use Revolution\DiscordManager\DiscordManager;
use Revolution\DiscordManager\Console;

use RestCord\DiscordClient;
use React\EventLoop\Factory as React;
use CharlotteDunois\Yasmin\Client as Yasmin;

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
                $this->app['config']->get('services.discord.yasmin', []),
                React::create()
            );
        });
    }
}
