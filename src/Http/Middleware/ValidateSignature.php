<?php

namespace Revolution\DiscordManager\Http\Middleware;

use Closure;
use Discord\Interaction;
use Discord\InteractionType;
use Illuminate\Http\Request;
use Revolution\DiscordManager\Http\Response\PongResponse;

class ValidateSignature
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
        abort_unless($request->hasHeader('X-Signature-Ed25519'), 401, 'Request does not contain signature');

        abort_unless($request->hasHeader('X-Signature-Timestamp'), 401, 'Request does not contain signature timestamp');

        abort_unless($this->validateSignature($request), 401, 'Invalid signature has given');

        if ($request->json('type') === InteractionType::PING) {
            return app()->call(PongResponse::class);
        }

        return $next($request); // @codeCoverageIgnore
    }

    protected function validateSignature(Request $request): bool
    {
        return Interaction::verifyKey(
            $request->getContent(),
            $request->header('X-Signature-Ed25519'),
            $request->header('X-Signature-Timestamp'),
            config('services.discord.public_key')
        );
    }
}
