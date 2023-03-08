<?php

declare(strict_types=1);

namespace Revolution\DiscordManager\Support;

// https://discord.com/developers/docs/interactions/application-commands#application-command-object-application-command-types
enum CommandType: int
{
    case CHAT_INPUT = 1;
    case USER = 2;
    case MESSAGE = 3;
}
