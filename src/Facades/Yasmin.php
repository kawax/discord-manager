<?php

namespace Revolution\DiscordManager\Facades;

use CharlotteDunois\Yasmin\Client;
use CharlotteDunois\Yasmin\Models\ClientUser;
use Illuminate\Support\Facades\Facade;
use React\EventLoop\LoopInterface;
use React\Promise\ExtendedPromiseInterface;

/**
 * @method static ClientUser|null user()
 * @method static LoopInterface getLoop()
 * @method static ExtendedPromiseInterface login(string $token, bool $force = false)
 *
 * @method static on(string $event, callable $listener)
 * @method static once(string $event, callable $listener)
 * @method static removeListener(string $event, callable $listener)
 * @method static removeAllListeners($event = null)
 *
 * @see \CharlotteDunois\Yasmin\Client
 *
 * @codeCoverageIgnore
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

    /**
     * @param  string  $method
     * @param  array  $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        if (isset(static::getFacadeRoot()->$method)) {
            return static::getFacadeRoot()->$method;
        } elseif (is_callable([static::getFacadeRoot(), $method])) {
            return parent::__callStatic($method, $args); // @codeCoverageIgnore
        }

        throw new \BadMethodCallException(
            sprintf('Method %s::%s does not exist.', static::class, $method) // @codeCoverageIgnore
        );
    }
}
