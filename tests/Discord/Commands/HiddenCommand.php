<?php

namespace Tests\Discord\Commands;

use Discord\Parts\Channel\Message;

class HiddenCommand
{
    /**
     * @var string
     */
    public $command = 'hide';

    /**
     * @var bool
     */
    public $hidden = true;

    /**
     * @param  Message  $message
     *
     * @return string
     */
    public function __invoke(Message $message)
    {
        $message->reply('hide!');
    }
}
