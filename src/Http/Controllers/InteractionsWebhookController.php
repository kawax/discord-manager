<?php

namespace Revolution\DiscordManager\Http\Controllers;

use Illuminate\Http\Request;
use Revolution\DiscordManager\Contracts\InteractionsEvent;
use Revolution\DiscordManager\Contracts\InteractionsResponse;

class InteractionsWebhookController
{
    public function __invoke(Request $request)
    {
        dispatch(app(InteractionsEvent::class));

        return app()->call(InteractionsResponse::class);
    }
}
