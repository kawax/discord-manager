<?php

declare(strict_types=1);

use Revolution\DiscordManager\Support\CommandOptionType;
use Revolution\DiscordManager\Support\CommandType;

/**
 * Discord Interactions Commands.
 */
return [
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

    // API Version
    'version' => env('DISCORD_API_VERSION', 10),

    // Commands path
    'commands' => app_path('Discord/Interactions'),

    // Bot token
    'token' => env('DISCORD_BOT_TOKEN'),

    // APPLICATION ID
    'bot' => env('DISCORD_BOT'),

    // PUBLIC KEY
    'public_key' => env('DISCORD_PUBLIC_KEY'),

    // URI path
    'path' => 'discord/webhook',

    // Route name
    'route' => 'discord.webhook',

    // Route middleware
    'middleware' => 'throttle',
];
