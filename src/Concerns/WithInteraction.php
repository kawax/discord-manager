<?php

namespace Revolution\DiscordManager\Concerns;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait WithInteraction
{
    /**
     * @param  string  $token
     * @param  array  $data
     * @return Response
     */
    public function followup(string $token, array $data): Response
    {
        $app_id = config('services.discord.bot');

        return Http::discord()->post("/webhooks/$app_id/$token", $data);
    }
}