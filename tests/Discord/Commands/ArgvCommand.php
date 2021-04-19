<?php

namespace Tests\Discord\Commands;

use Discord\Parts\Channel\Message;
use Revolution\DiscordManager\Concerns\Input;

class ArgvCommand
{
    use Input;

    /**
     * @var string
     */
    public $command = 'argv {test} {--option=}';

    /**
     * @param  Message  $message
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke(Message $message)
    {
        $input = $this->input(explode(' ', $message->content));

        $message->reply('argv! '.$input->getArgument('test').' '.$input->getOption('option'))
            ->done(function (Message $message) {

            });
    }
}
