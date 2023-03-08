<?php

namespace Tests;

use Revolution\DiscordManager\Providers\DiscordInteractionsServiceProvider;
use Revolution\DiscordManager\Support\CommandOptionType;
use Revolution\DiscordManager\Support\CommandType;
use Revolution\DiscordManager\Support\Intents;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            DiscordInteractionsServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'DiscordManager' => \Revolution\DiscordManager\Facades\DiscordManager::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('discord_interactions', [
            'guild' => [
                [
                    'name' => 'test',
                    'description' => 'test command',
                    'type' => CommandType::CHAT_INPUT,
                    'guild_id' => env('DISCORD_GUILD'),
                    'options' => [
                        [
                            'name' => 'message',
                            'description' => 'optional message',
                            'type' => CommandOptionType::STRING,
                        ],
                    ],
                ],
            ],

            'global' => [
                [
                    'name' => 'hello',
                    'description' => 'hello command',
                    'type' => CommandType::CHAT_INPUT,
                ],
            ],

            'commands' => __DIR__.'/Discord/Interactions',

            //Bot token
            'token' => 'test',
            //APPLICATION ID
            'bot' => '1',
            //PUBLIC KEY
            'public_key' => 'test',

            'path' => 'discord/webhook',
            'route' => 'discord.webhook',
            'middleware' => 'throttle',
        ]);
    }
}
