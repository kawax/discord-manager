<?php

namespace Tests\Discord\Commands;

use CharlotteDunois\Yasmin\Models\Message;

use Illuminate\Console\Parser;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;

class ArgvCommand
{
    /**
     * @var string
     */
    public $command = 'argv {test} {--option=}';

    /**
     * @param Message $message
     *
     * @return string
     */
    public function __invoke(Message $message)
    {
        [$name, $args, $options] = Parser::parse($this->command);

        $definition = new InputDefinition();
        $definition->setArguments($args);
        $definition->setOptions($options);

        $argv = explode(' ', $message->cleanContent);

        $input = new ArgvInput($argv, $definition);

        return 'argv! ' . $input->getArgument('test') . ' ' . $input->getOption('option');
    }
}
