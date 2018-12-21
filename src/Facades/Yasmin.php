<?php

namespace Revolution\DiscordManager\Facades;

use Illuminate\Support\Facades\Facade;

use CharlotteDunois\Yasmin\Client;

class Yasmin extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }
}
