<?php

namespace Revolution\DiscordManager\Contracts;

use Illuminate\Http\Request;

interface InteractionsResponse
{
    /**
     * @param  Request  $request
     * @return mixed
     */
    public function __invoke(Request $request): mixed;
}
