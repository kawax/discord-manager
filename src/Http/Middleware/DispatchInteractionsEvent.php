<?php

declare(strict_types=1);

namespace Revolution\DiscordManager\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Revolution\DiscordManager\Contracts\InteractionsEvent;

class DispatchInteractionsEvent
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        return $next($request);
    }

    public function terminate(Request $request, Response|JsonResponse $response): void
    {
        if ($response->isSuccessful()) {
            event(app(InteractionsEvent::class));
        }
    }
}
