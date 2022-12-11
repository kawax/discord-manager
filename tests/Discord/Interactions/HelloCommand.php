<?php

namespace Tests\Discord\Interactions;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Revolution\DiscordManager\Concerns\WithInteraction;

class HelloCommand
{
    use WithInteraction;

    /**
     * @var string
     */
    public string $command = 'hello';

    /**
     * @param  Request  $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $user = $request->json('member.user.id', $request->json('user.id'));

        $data = [
            'content' => "<@$user> Hello!",
            'allowed_mentions' => ['parse' => ['users']],
        ];

        return $this->followup(token: $request->json('token'), data: $data);
    }
}
