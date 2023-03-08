<?php

namespace Revolution\DiscordManager\Contracts;

use Illuminate\Http\Request;

interface InteractionsResponse
{
    public function __invoke(Request $request): mixed;
}
