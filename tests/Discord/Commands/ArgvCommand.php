<?php

namespace Tests\Discord\Commands;

use CharlotteDunois\Yasmin\Models\Message;

use Revolution\DiscordManager\Traits\Input;

class ArgvCommand
{
    use Input;

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
        $input = $this->input(explode(' ', $message->cleanContent));

        return 'argv! ' . $input->getArgument('test') . ' ' . $input->getOption('option');
    }
}
