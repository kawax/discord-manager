<?php

declare(strict_types=1);

namespace Revolution\DiscordManager\Console;

use Illuminate\Console\GeneratorCommand;

class MakeInteraction extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'discord:make:interaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Discord interaction command';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Discord command';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__.'/stubs/interaction.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Discord\Interactions';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [];
    }
}
