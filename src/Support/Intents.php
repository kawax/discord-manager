<?php

namespace Revolution\DiscordManager\Support;

class Intents
{
    public const GUILDS = 'GUILDS';
    public const GUILD_MEMBERS = 'GUILD_MEMBERS';
    public const GUILD_BANS = 'GUILD_BANS';
    public const GUILD_EMOJIS = 'GUILD_EMOJIS';
    public const GUILD_INTEGRATIONS = 'GUILD_INTEGRATIONS';
    public const GUILD_WEBHOOKS = 'GUILD_WEBHOOKS';
    public const GUILD_INVITES = 'GUILD_INVITES';
    public const GUILD_VOICE_STATES = 'GUILD_VOICE_STATES';
    public const GUILD_PRESENCES = 'GUILD_PRESENCES';
    public const GUILD_MESSAGES = 'GUILD_MESSAGES';
    public const GUILD_MESSAGE_REACTIONS = 'GUILD_MESSAGE_REACTIONS';
    public const GUILD_MESSAGE_TYPING = 'GUILD_MESSAGE_TYPING';
    public const DIRECT_MESSAGES = 'DIRECT_MESSAGES';
    public const DIRECT_MESSAGE_REACTIONS = 'DIRECT_MESSAGE_REACTIONS';
    public const DIRECT_MESSAGE_TYPING = 'DIRECT_MESSAGE_TYPING';

    /**
     * All : 32767
     * Without GUILD_MEMBERS, GUILD_PRESENCES and typing : 14077 (default)
     * Without typing : 14335.
     *
     * @var array
     *
     * @see https://discord.com/developers/docs/topics/gateway#gateway-intents
     */
    protected const INTENTS = [
        self::GUILDS                   => (1 << 0),
        self::GUILD_MEMBERS            => (1 << 1),
        self::GUILD_BANS               => (1 << 2),
        self::GUILD_EMOJIS             => (1 << 3),
        self::GUILD_INTEGRATIONS       => (1 << 4),
        self::GUILD_WEBHOOKS           => (1 << 5),
        self::GUILD_INVITES            => (1 << 6),
        self::GUILD_VOICE_STATES       => (1 << 7),
        self::GUILD_PRESENCES          => (1 << 8),
        self::GUILD_MESSAGES           => (1 << 9),
        self::GUILD_MESSAGE_REACTIONS  => (1 << 10),
        self::GUILD_MESSAGE_TYPING     => (1 << 11),
        self::DIRECT_MESSAGES          => (1 << 12),
        self::DIRECT_MESSAGE_REACTIONS => (1 << 13),
        self::DIRECT_MESSAGE_TYPING    => (1 << 14),
    ];

    /**
     * @return array
     */
    public static function all(): array
    {
        return static::INTENTS;
    }

    /**
     * @return array
     */
    public static function default(): array
    {
        return static::except([
            self::GUILD_MEMBERS,
            self::GUILD_PRESENCES,
            self::GUILD_MESSAGE_TYPING,
            self::DIRECT_MESSAGE_TYPING,
        ]);
    }

    /**
     * @param  array  $only
     * @return array
     */
    public static function only(array $only): array
    {
        return array_filter(static::INTENTS, function ($key) use ($only) {
            return in_array($key, $only);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param  array  $except
     * @return array
     */
    public static function except(array $except): array
    {
        return array_filter(static::INTENTS, function ($key) use ($except) {
            return ! in_array($key, $except);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param  array  $intents
     * @return int
     */
    public static function bit(array $intents): int
    {
        $value = 0;

        foreach ($intents as $intent) {
            $value |= $intent;
        }

        return $value;
    }
}
