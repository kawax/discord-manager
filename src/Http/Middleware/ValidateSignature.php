<?php

namespace Revolution\DiscordManager\Http\Middleware;

use Closure;
use Discord\InteractionType;
use Illuminate\Http\Request;
use Discord\Interaction;
use Discord\InteractionResponseType;

class ValidateSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        info($request);
        info($request->headers);

        if (! $request->hasHeader('X-Signature-Ed25519')) {
            abort(401, 'Request does not contain signature');
        }

        if (! $request->hasHeader('X-Signature-Timestamp')) {
            abort(401, 'Request does not contain signature timestamp');
        }

        if (! $this->validateSignature($request)) {
            abort(401, 'Invalid signature has given');
        }

        if ($request->json('type') === InteractionType::PING) {
            info('pong');

            return ['type' => InteractionResponseType::PONG];
        }

        return $next($request); // @codeCoverageIgnore
    }

    protected function validateSignature(Request $request): bool
    {
        return Interaction::verifyKey(
            $request->getContent(),
            $request->header('X-Signature-Ed25519'),
            $request->header('X-Signature-Timestamp'),
            config('services.discord.token')
        );
    }
}
