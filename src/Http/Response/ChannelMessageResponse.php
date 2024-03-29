<?php

declare(strict_types=1);

namespace Revolution\DiscordManager\Http\Response;

use Discord\InteractionResponseType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Revolution\DiscordManager\Contracts\InteractionsResponse;

/**
 * @codeCoverageIgnore
 */
class ChannelMessageResponse implements InteractionsResponse
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'type' => InteractionResponseType::CHANNEL_MESSAGE_WITH_SOURCE,
            'data' => [
                'content' => 'Hi! <@'.$request->json('member.user.id', $request->json('user.id')).'>',
                'allowed_mentions' => ['parse' => ['users']],
            ],
        ]);
    }
}
