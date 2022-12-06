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

        if (! $request->hasHeader('HTTP_X_SIGNATURE_ED25519')) {
            abort(401, 'Request does not contain signature');
        }

        if (! $request->hasHeader('HTTP_X_SIGNATURE_TIMESTAMP')) {
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
            $request->header('HTTP_X_SIGNATURE_ED25519'),
            $request->header('HTTP_X_SIGNATURE_TIMESTAMP'),
            config('services.discord.token')
        );
    }
}
