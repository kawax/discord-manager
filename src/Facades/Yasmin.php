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

    public static function __callStatic($method, $args)
    {
        try {
            return static::getFacadeRoot()->$method;
        } catch (\Exception $e) {
            throw new \BadMethodCallException(sprintf(
                'Method %s::%s does not exist.', static::class, $method
            ));
        }
    }
}
