<?php

namespace Revolution\DiscordManager\Facades;

use Illuminate\Support\Facades\Facade;

use RestCord\DiscordClient;

class RestCord extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return DiscordClient::class;
    }
}
