<?php

namespace Revolution\DiscordManager\Facades;

use Discord\Discord;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

/**
 * @method static on($event, callable $listener)
 * @method static run()
 */
#[CodeCoverageIgnore]
class DiscordPHP extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return Discord::class;
    }
}
