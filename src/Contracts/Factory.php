<?php

namespace Revolution\DiscordManager\Contracts;

use Discord\Parts\Channel\Message;

interface Factory
{
    /**
     * @param  Message  $message
     * @return void
     */
    public function command(Message $message): void;

    /**
     * @param  Message  $message
     * @return void
     */
    public function direct(Message $message): void;
}
