<?php

namespace Revolution\DiscordManager\Http\Controllers;

use Illuminate\Http\Request;

class InteractionsWebhookController
{
    public function __invoke(Request $request)
    {
        info($request->getContent());
    }
}
