<?php

namespace Revolution\DiscordManager\Contracts;

use CharlotteDunois\Yasmin\Models\Message;

interface Factory
{
    /**
     * @param  \CharlotteDunois\Yasmin\Models\Message  $message
     *
     * @return string
     */
    public function command(Message $message);

    /**
     * @param  \CharlotteDunois\Yasmin\Models\Message  $message
     *
     * @return string
     */
    public function direct(Message $message);
}
