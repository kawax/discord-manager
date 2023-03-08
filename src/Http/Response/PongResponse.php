<?php

declare(strict_types=1);

namespace Revolution\DiscordManager\Http\Response;

use Discord\InteractionResponseType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Revolution\DiscordManager\Contracts\InteractionsResponse;

class PongResponse implements InteractionsResponse
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'type' => InteractionResponseType::PONG,
        ]);
    }
}
