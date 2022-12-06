<?php

namespace Revolution\DiscordManager\Http\Response;

use Discord\InteractionResponseType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Revolution\DiscordManager\Contracts\InteractionsResponse;

class ChannelMessageResponse implements InteractionsResponse
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'type' => InteractionResponseType::CHANNEL_MESSAGE_WITH_SOURCE,
            'data' => [
                'content' => 'Hi! '.$request->json('member.user.username'),
            ],
        ]);
    }
}
