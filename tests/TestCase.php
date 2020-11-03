<?php

namespace Tests;

use Discord\WebSockets\Event;
use Revolution\DiscordManager\Providers\DiscordManagerServiceProvider;
use Revolution\DiscordManager\Support\Intents;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            DiscordManagerServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'DiscordManager' => \Revolution\DiscordManager\Facades\DiscordManager::class,
            'Yasmin'         => \Revolution\DiscordManager\Facades\Yasmin::class,
            'RestCord'       => \Revolution\DiscordManager\Facades\RestCord::class,
            'DiscordPHP'       => \Revolution\DiscordManager\Facades\DiscordPHP::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('services.discord', [
            'prefix'     => '/',
            'not_found'  => 'Command Not Found!',
            'path'       => [
                'commands' => __DIR__.'/Discord/Commands',
                'directs'  => __DIR__.'/Discord/Directs',
            ],
            'token'      => 'test',
            'channel'    => '1',
            'bot'        => '2',
            'yasmin'     => [
                'ws.disabledEvents' => [
                    'TYPING_START',
                ],
            ],
            'discord-php' => [
                'disabledEvents' => [
                    Event::TYPING_START,
                ],
                'intents'        => array_sum(Intents::default()),
            ],
        ]);
    }
}
