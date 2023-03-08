<?php

declare(strict_types=1);

namespace Revolution\DiscordManager\Concerns;

use Illuminate\Http\Client\Response;
use Revolution\DiscordManager\Facades\DiscordManager;

trait WithInteraction
{
    public function followup(string $token, array $data): Response
    {
        $app_id = config('discord_interactions.bot');

        return DiscordManager::http()->post("/webhooks/$app_id/$token", $data);
    }
}
