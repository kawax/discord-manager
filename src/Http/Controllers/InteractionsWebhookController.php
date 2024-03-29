<?php

declare(strict_types=1);

namespace Revolution\DiscordManager\Http\Controllers;

use Illuminate\Http\Request;
use Revolution\DiscordManager\Contracts\InteractionsResponse;

class InteractionsWebhookController
{
    public function __invoke(Request $request): mixed
    {
        return app()->call(InteractionsResponse::class);
    }
}
