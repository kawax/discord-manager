<?php

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
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        return $next($request);
    }

    /**
     * @param  Request  $request
     * @param  JsonResponse|Response  $response
     * @return void
     */
    public function terminate(Request $request, Response|JsonResponse $response): void
    {
        if ($response->isSuccessful()) {
            event(app(InteractionsEvent::class));
        }
    }
}
