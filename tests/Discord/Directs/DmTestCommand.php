<?php

namespace Tests\Discord\Directs;

use Discord\Parts\Channel\Message;

class DmTestCommand
{
    /**
     * @var string
     */
    public string $command = 'test';

    /**
     * @param  Message  $message
     * @return void
     */
    public function __invoke(Message $message)
    {
        $message->reply('dm test! '.$message->author?->username)
            ->done(function (Message $message) {
            });
    }
}
