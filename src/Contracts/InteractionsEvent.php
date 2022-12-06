<?php

namespace Revolution\DiscordManager\Contracts;

use Illuminate\Http\Request;

interface InteractionsEvent
{
    /**
     * @param  Request  $request
     */
    public function __construct(Request $request);
}
