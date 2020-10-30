<?php

namespace Revolution\DiscordManager\Facades;

use CharlotteDunois\Yasmin\Models\Message;
use Illuminate\Support\Facades\Facade;
use Revolution\DiscordManager\Contracts\Factory;

/**
 *
 * @method static string command(Message $message)
 * @method static string direct(Message $message)
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
