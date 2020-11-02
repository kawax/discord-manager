<?php

namespace Tests\Discord\Commands;

use Discord\Parts\Channel\Message;

class TestCommand
{
    /**
     * @var string
     */
    public $command = 'test';

    /**
     * @param  Message  $message
     *
     * @return string
     */
    public function __invoke(Message $message)
    {
        return 'test! '.$message->author->username;
    }
}
