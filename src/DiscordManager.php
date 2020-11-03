<?php

namespace Revolution\DiscordManager;

use Discord\Parts\Channel\Message;
use Illuminate\Console\Parser;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;
use Revolution\DiscordManager\Contracts\Factory;
use Revolution\DiscordManager\Exceptions\CommandNotFountException;
use Symfony\Component\Finder\Finder;

class DiscordManager implements Factory
{
    public const COMMANDS = 'commands';

    public const DIRECTS = 'directs';

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $not_found;

    /**
     * @var array
     */
    protected $commands = [];

    /**
     * @var array
     */
    protected $directs = [];

    /**
     * DiscordManager constructor.
     *
     * @param  array  $config
     */
    public function __construct($config)
    {
        $this->prefix = data_get($config, 'prefix', '/');
        $this->not_found = data_get($config, 'not_found', 'Command Not Found!');

        $this->load(data_get($config, 'path.commands', app()->path('Discord/Commands')), self::COMMANDS);
        $this->load(data_get($config, 'path.directs', app()->path('Discord/Directs')), self::DIRECTS);
    }

    /**
     * @param  Message  $message
     *
     * @return void
     */
    public function command(Message $message): void
    {
        $this->invoke($message, self::COMMANDS);
    }

    /**
     * @param  Message  $message
     *
     * @return void
     */
    public function direct(Message $message): void
    {
        $this->invoke($message, self::DIRECTS);
    }

    /**
     * @param  Message  $message
     * @param  string  $type
     *
     * @return void
     * @throws CommandNotFountException
     */
    protected function invoke(Message $message, $type = self::COMMANDS): void
    {
        if (! Str::contains(data_get($message, 'content'), $this->prefix)) {
            return; // @codeCoverageIgnore
        }

        [$command] = Parser::parse(Str::after(data_get($message, 'content'), $this->prefix));

        if (Arr::has($this->$type, $command) and is_callable([($this->$type)[$command], '__invoke'])) {
            ($this->$type)[$command]($message);
        } else {
            throw new CommandNotFountException($this->not_found);
        }
    }

    /**
     * @param  string|array  $paths
     * @param  string  $type
     */
    protected function load($paths, string $type)
    {
        $paths = array_unique(Arr::wrap($paths));

        $paths = array_filter(
            $paths,
            function ($path) {
                return is_dir($path);
            }
        );

        if (empty($paths)) {
            return;
        }

        $namespace = app()->getNamespace();

        foreach ((new Finder())->in($paths)->files() as $command) {
            $command = $namespace.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($command->getPathname(), app()->path().DIRECTORY_SEPARATOR)
                );

            $this->add($command, $type);
        }
    }

    /**
     * @param  string  $command
     * @param  string  $type
     */
    public function add(string $command, string $type = self::COMMANDS)
    {
        try {
            if ((new ReflectionClass($command))->isAbstract()) {
                return; // @codeCoverageIgnore
            }

            $cmd = app($command);

            [$name] = Parser::parse($cmd->command);

            if (($cmd->hidden ?? false)) {
                return;
            }

            if ($type === self::COMMANDS) {
                $this->commands[$name] = $cmd;
            } elseif ($type === self::DIRECTS) {
                $this->directs[$name] = $cmd;
            }
        } catch (\ReflectionException $e) {
            return;
        }
    }
}
