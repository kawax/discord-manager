<?php

namespace Tests\Discord\Commands;

use Discord\Parts\Channel\Message;

class HiddenCommand
{
    /**
     * @var string
     */
    public string $command = 'hide';

    /**
     * @var bool
     */
    public bool $hidden = true;

    /**
     * @param  Message  $message
     * @return void
     */
    public function __invoke(Message $message)
    {
        $message->reply('hide!')
            ->done(function (Message $message) {
            });
    }
}
