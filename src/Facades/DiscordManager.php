<?php

namespace Revolution\DiscordManager\Facades;

use Illuminate\Support\Facades\Facade;
use Revolution\DiscordManager\Contracts\Factory;

/**
 * @method static string command(\Discord\Parts\Channel\Message $message)
 * @method static string direct(\Discord\Parts\Channel\Message $message)
 * @method static string interaction(\Illuminate\Http\Request $request)
 *
 * @see \Revolution\DiscordManager\DiscordManager
 */
class DiscordManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
