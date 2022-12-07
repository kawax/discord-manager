<?php

namespace Revolution\DiscordManager\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Revolution\DiscordManager\Contracts\InteractionsEvent;

class InteractionsWebhook implements InteractionsEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Request $request)
    {
        //
    }
}
