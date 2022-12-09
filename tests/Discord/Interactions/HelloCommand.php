<?php

namespace Tests\Discord\Interactions;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HelloCommand
{
    /**
     * @var  string
     */
    public string $command = 'hello';

    /**
     * @param  Request  $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $app_id = config('services.discord.bot');
        $token = $request->json('token');

        $user = $request->json('member.user.id', $request->json('user.id'));

        $data = [
            'content' => "<@$user> Hello!",
            'allowed_mentions' => ['parse' => ['users']],
        ];

        return Http::discord()->post("/webhooks/$app_id/$token", $data);
    }
}
