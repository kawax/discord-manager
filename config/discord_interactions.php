<?php

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
        ]
    ],
    'global' => [
        [
            'name' => 'hello',
            'description' => 'hello command',
            'type' => CommandType::CHAT_INPUT,
        ]
    ]
];
