<?php

namespace Revolution\DiscordManager\Concerns;

use Illuminate\Console\Parser;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

trait Input
{
    /**
     * @param  array  $argv
     * @return InputInterface
     */
    public function input(array $argv): InputInterface
    {
        [$name, $args, $options] = Parser::parse($this->command);

        $definition = new InputDefinition();
        $definition->setArguments($args);
        $definition->setOptions($options);

        return new ArgvInput($argv, $definition);
    }
}
