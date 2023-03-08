<?php

namespace Revolution\DiscordManager\Console;

use Illuminate\Console\Command;
use Illuminate\Http\Client\Response;
use Revolution\DiscordManager\Facades\DiscordManager;

class RegisterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord:interactions:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register Interactions Commands';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->guild();

        $this->newLine();

        $this->global();

        return Command::SUCCESS;
    }

    protected function guild(): void
    {
        $this->info('Registering Guild Commands');

        collect(config('discord_interactions.guild'))
            ->groupBy('guild_id')
            ->each(function ($commands, $guild_id) {
                $this->line('Guild : '.$guild_id);

                $app_id = config('discord_interactions.bot');

                $data = collect($commands)->except(['guild_id'])->toArray();

                /** @var Response $response */
                $response = DiscordManager::http()->put("/applications/$app_id/guilds/$guild_id/commands", $data);

                if ($response->successful()) {
                    $this->info('Succeeded.');
                } else {
                    $this->error('Failed : '.$response->body()); // @codeCoverageIgnore
                }
            });
    }

    protected function global(): void
    {
        $this->info('Registering Global Commands');

        $app_id = config('discord_interactions.bot');

        $data = config('discord_interactions.global');

        /** @var Response $response */
        $response = DiscordManager::http()->put("/applications/$app_id/commands", $data);

        if ($response->successful()) {
            $this->info('Succeeded.');
        } else {
            $this->error('Failed : '.$response->body()); // @codeCoverageIgnore
        }
    }
}
