# UPGRADING

## v3.0
- Delete Yasmin
- Update DiscordPHP v6 https://github.com/discord-php/DiscordPHP/blob/master/V6_CONVERSION.md
- Update all $message->reply()

```php
$message->reply('test')
        ->done(function ($message) {
        });
```


## v2.1
If you want to use Yasmin again, you can install it separately.

```
composer require laravel-discord/yasmin
```

## v2.0
Using `team-reflex/discord-php` instead Yasmin.

### Changed to use `Discord\Parts\Channel\Message` in all Discord commands.
Reply is executed in the command.

```php
<?php

namespace App\Discord\Commands;

use Discord\Parts\Channel\Message;

class TestCommand
{
    /**
     * @var string
     */
    public $command = 'test';

    /**
     * @param  Message  $message
     *
     * @return void
     * @throws \Exception
     */
    public function __invoke(Message $message)
    {
        $message->reply('test command');
    }
}
```

### Update ServeCommand

```php
<?php

namespace App\Console\Commands;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Illuminate\Console\Command;
use Revolution\DiscordManager\Exceptions\CommandNotFountException;
use Revolution\DiscordManager\Facades\DiscordManager;
use Revolution\DiscordManager\Facades\DiscordPHP;

class ServeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        DiscordPHP::on('error', function ($error) {
            $this->error($error);
        });

        DiscordPHP::on('ready', function (Discord $discord) {
            $this->info('Logged in as '.$discord->user->username);

            $discord->on('message', function (Message $message) {
                $this->info("Recieved a message from {$message->author->username}: {$message->content}");

                try {
                    if ($message->channel->is_private) {
                        DiscordManager::direct($message);
                    } elseif ($message->mentions->has(config('services.discord.bot'))) {
                        DiscordManager::command($message);
                    }
                } catch (CommandNotFountException $e) {
                    $message->reply($e->getMessage());
                }
            });
        });

        DiscordPHP::run();
    }
}
```
