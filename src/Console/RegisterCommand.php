<?php

namespace Revolution\DiscordManager\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RegisterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord:command:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register Application Commands';

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
        collect(config('discord_commands.guild'))
            ->groupBy('guild_id')
            ->each(function ($commands, $guild_id) {
                $app_id = config('services.discord.bot');
                $data = collect($commands)->except(['guild_id'])->toArray();
                $response = Http::withHeaders([
                    'Authorization' => 'Bot '.config('services.discord.token')
                ])->put("https://discord.com/api
/v10/applications/$app_id/guilds/$guild_id/commands", $data);

                if ($response->successful()) {
                    $this->info($response->status());
                }

            });
    }
}
