<?php

namespace Revolution\DiscordManager\Console;

use Illuminate\Console\Command;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

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
     * @return void
     */
    public function handle()
    {
        $this->guild();

        $this->newLine();

        $this->global();
    }

    protected function guild()
    {
        $this->info('Registering Guild Commands');

        collect(config('discord_interactions.guild'))
            ->groupBy('guild_id')
            ->each(function ($commands, $guild_id) {
                $this->line('Guild : '.$guild_id);

                $app_id = config('services.discord.bot');

                $data = collect($commands)->except(['guild_id'])->toArray();

                /**
                 * @var Response $response
                 */
                $response = Http::discord()->put("/applications/$app_id/guilds/$guild_id/commands", $data);

                if ($response->successful()) {
                    $this->info('Succeeded.');
                } else {
                    $this->error('Failed : '.$response->body());
                }
            });
    }

    protected function global()
    {
        $this->info('Registering Global Commands');

        $app_id = config('services.discord.bot');

        $data = config('discord_interactions.global');

        /**
         * @var Response $response
         */
        $response = Http::discord()->put("/applications/$app_id/commands", $data);

        if ($response->successful()) {
            $this->info('Succeeded.');
        } else {
            $this->error('Failed : '.$response->body());
        }
    }
}
