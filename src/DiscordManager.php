<?php

declare(strict_types=1);

namespace Revolution\DiscordManager;

use Illuminate\Console\Parser;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use ReflectionClass;
use Revolution\DiscordManager\Contracts\Factory;
use Revolution\DiscordManager\Exceptions\CommandNotFountException;
use Symfony\Component\Finder\Finder;

class DiscordManager implements Factory
{
    protected array $interactions = [];

    public function __construct()
    {
        $this->load();
    }

    /**
     * @throws CommandNotFountException
     */
    public function interaction(Request $request): mixed
    {
        $name = $request->json('data.name', $request->json('data.custom_id'));

        if (Arr::has($this->interactions, $name) && is_callable($cmd = $this->interactions[$name])) {
            return $cmd($request);
        }

        throw new CommandNotFountException('Command Not Found! : '.$name);
    }

    public function http(int $version = 10): PendingRequest
    {
        return Http::withToken(token: config('discord_interactions.token'),
            type: 'Bot')
                   ->baseUrl('https://discord.com/api/v'.$version);
    }

    protected function load(): void
    {
        $paths = array_unique(Arr::wrap(config('discord_interactions.commands')));

        $paths = array_filter(
            $paths,
            fn ($path) => is_dir($path)
        );

        if (empty($paths)) {
            return; // @codeCoverageIgnore
        }

        $namespace = app()->getNamespace();

        foreach ((new Finder())->in($paths)->files() as $command) {
            $command = $namespace.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($command->getPathname(), app()->path().DIRECTORY_SEPARATOR)
                );

            $this->add($command);
        }
    }

    public function add(string $command): void
    {
        try {
            if ((new ReflectionClass($command))->isAbstract()) {
                return; // @codeCoverageIgnore
            }
        } catch (\ReflectionException) {
            return;
        }

        $cmd = app($command);

        [$name] = Parser::parse($cmd->command);

        if (($cmd->hidden ?? false)) {
            return; // @codeCoverageIgnore
        }

        $this->interactions[$name] = $cmd;
    }
}
