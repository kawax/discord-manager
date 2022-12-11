<?php

namespace Revolution\DiscordManager\Http\Controllers;

use Illuminate\Http\Request;
use Revolution\DiscordManager\Contracts\InteractionsResponse;

class InteractionsWebhookController
{
    public function __invoke(Request $request)
    {
        return app()->call(InteractionsResponse::class);
    }
}
