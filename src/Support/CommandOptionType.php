<?php

namespace Revolution\DiscordManager\Support;

// https://discord.com/developers/docs/interactions/application-commands#application-command-object-application-command-option-type
class CommandOptionType
{
    public const SUB_COMMAND = 1;
    public const SUB_COMMAND_GROUP = 2;
    public const STRING = 3;
    public const INTEGER = 4;
    public const BOOLEAN = 5;
    public const USER = 6;
    public const CHANNEL = 7;
    public const ROLE = 8;
    public const MENTIONABLE = 9;
    public const NUMBER = 10;
    public const ATTACHMENT = 11;
}
