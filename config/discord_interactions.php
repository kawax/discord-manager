<?php

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
];
