<?php

namespace Revolution\DiscordManager\Contracts;

use Illuminate\Http\Request;

interface InteractionsEvent
{
    public function __construct(Request $request);
}
