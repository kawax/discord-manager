<?php

namespace Revolution\DiscordManager\Contracts;

use Illuminate\Http\Request;

interface Factory
{
    public function interaction(Request $request): mixed;
}
