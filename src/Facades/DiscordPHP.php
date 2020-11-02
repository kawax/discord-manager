<?php

namespace Revolution\DiscordManager\Facades;

use Illuminate\Support\Facades\Facade;
use Discord\Discord;

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
