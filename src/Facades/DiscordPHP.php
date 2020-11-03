<?php

namespace Revolution\DiscordManager\Facades;

use Discord\Discord;
use Illuminate\Support\Facades\Facade;

/**
 * @method static on($event, callable $listener)
 * @method static run()
 */
class DiscordPHP extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Discord::class;
    }
}
