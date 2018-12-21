<?php

namespace Revolution\DiscordManager\Facades;

use Illuminate\Support\Facades\Facade;

use Revolution\DiscordManager\Contracts\Factory;

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
