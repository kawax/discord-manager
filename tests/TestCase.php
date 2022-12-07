<?php

namespace Tests;

use Discord\WebSockets\Event;
use Revolution\DiscordManager\Providers\DiscordInteractionsServiceProvider;
use Revolution\DiscordManager\Providers\DiscordManagerServiceProvider;
use Revolution\DiscordManager\Support\Intents;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            DiscordManagerServiceProvider::class,
            DiscordInteractionsServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'DiscordManager' => \Revolution\DiscordManager\Facades\DiscordManager::class,
            'RestCord'       => \Revolution\DiscordManager\Facades\RestCord::class,
            'DiscordPHP'       => \Revolution\DiscordManager\Facades\DiscordPHP::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('services.discord', [
            'path'      => [
                'commands' => __DIR__.'/Discord/Commands',
                'directs'  => __DIR__.'/Discord/Directs',
                'interactions'  => __DIR__.'/Discord/Interactions',
            ],

            //Bot token
            'token'     => 'test',
            //APPLICATION ID
            'bot'       => '1',
            //PUBLIC KEY
            'public_key' => 'test',

            //Notification route
            'channel'   => '2',

            //Interactions command
            'interactions' => [
                'path' => 'discord/webhook',
                'route' => 'discord.webhook',
                'middleware' => 'throttle',
            ],

            //Gateway command
            'prefix'    => '/',
            'not_found' => 'Command Not Found!',
            'discord-php' => [
                'disabledEvents' => [
                    Event::TYPING_START,
                ],
                'intents'        => array_sum(Intents::default()),
            ],
        ]);
    }
}
