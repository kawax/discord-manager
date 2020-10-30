<?php

namespace Revolution\DiscordManager\Facades;

use CharlotteDunois\Yasmin\Client;
use CharlotteDunois\Yasmin\Models\ClientUser;
use Illuminate\Support\Facades\Facade;
use React\EventLoop\LoopInterface;

/**
 * @method static ClientUser|null user()
 * @method static LoopInterface getLoop()
 *
 * @method static on(string $event, callable $listener)
 * @method static once(string $event, callable $listener)
 * @method static removeListener(string $event, callable $listener)
 * @method static removeAllListeners($event = null)
 *
 * @see \CharlotteDunois\Yasmin\Client
 */
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
        if (isset(static::getFacadeRoot()->$method)) {
            return static::getFacadeRoot()->$method;
        } elseif (is_callable([static::getFacadeRoot(), $method])) {
            return parent::__callStatic($method, $args);
        }

        throw new \BadMethodCallException(
            sprintf(
                'Method %s::%s does not exist.',
                static::class,
                $method
            )
        );
    }
}
