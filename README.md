# Discord Manager

[![packagist](https://badgen.net/packagist/v/revolution/discord-manager)](https://packagist.org/packages/revolution/discord-manager)
[![Maintainability](https://api.codeclimate.com/v1/badges/27e52e9ba3df10623fae/maintainability)](https://codeclimate.com/github/kawax/discord-manager/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/27e52e9ba3df10623fae/test_coverage)](https://codeclimate.com/github/kawax/discord-manager/test_coverage)

- https://github.com/kawax/discord-project
- https://github.com/kawax/arty

## Requirements
- PHP >= 8.0
- Laravel >= 9.0 or other illuminate base project

## Installation

```
composer require revolution/discord-manager
```

If you want to add a Discord notification channel.
```
composer require laravel-notification-channels/discord
```

### config/services.php
```php
use Revolution\DiscordManager\Support\Intents;
use Discord\WebSockets\Event;

return [
    'discord' => [
       'path'      => [
            'commands' => app_path('Discord/Commands'),
            'directs'  => app_path('Discord/Directs'),
            'interactions'  => app_path('Discord/Interactions'),
        ],

        //Bot token
        'token'     => env('DISCORD_BOT_TOKEN'),
        //APPLICATION ID
        'bot'       => env('DISCORD_BOT'),
        //PUBLIC KEY
        'public_key' => env('DISCORD_PUBLIC_KEY'),

        //Notification route
        'channel'   => env('DISCORD_CHANNEL'),

        //Interactions command
        'interactions' => [
            'path' => 'discord/webhook',
            'route' => 'discord.webhook',
            'middleware' => 'throttle',
        ],

        //Gateway command
        'prefix'    => '/',
        'not_found' => 'Command Not Found!',
        'discord-php' => [
            'disabledEvents' => [
                Event::TYPING_START,
            ],
            'intents'        => array_sum(Intents::default()),
        ],
    ],
];
```

### .env
```
DISCORD_BOT_TOKEN=
DISCORD_BOT=
DISCORD_PUBLIC_KEY=

DISCORD_GUILD=

DISCORD_CHANNEL=
```

## make Discord command
```
php artisan discord:make:command NewChannelCommand
php artisan discord:make:direct NewDmCommand
php artisan discord:make:interaction NewInteractionCommand
```

## Interactions
### Publish config file
```shell
php artisan vendor:publish --tag=discord-interactions-config
```

### Edit config/discord_interactions.php

### Create a command to respond
```shell
php artisan discord:make:interaction HelloCommand
```

### Register commands to Discord server
```shell
php artisan discord:interactions:register
```

### Create Event listener
```shell
php artisan make:listener InteractionsListener
```

```php
use Revolution\DiscordManager\Events\InteractionsWebhook;
use Revolution\DiscordManager\Facades\DiscordManager;

//

    /**
     * Handle the event.
     *
     * @param  InteractionsWebhook  $event
     * @return void
     */
    public function handle(InteractionsWebhook $event)
    {
        // Must use queue or dispatch()->afterResponse()

        // When not using a queue
        dispatch(fn () => DiscordManager::interaction($event->request))->afterResponse();

        // When using a queue
        //DiscordManager::interaction($event->request);
    }
```

EventServiceProvider.php
```php
use App\Listeners\InteractionsListener;
use Revolution\DiscordManager\Events\InteractionsWebhook;

//

    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        InteractionsWebhook::class => [
            InteractionsListener::class,
        ],
    ];
```

## DiscordPHP
```php
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Revolution\DiscordManager\Facades\DiscordPHP;


DiscordPHP::on('ready', function (Discord $discord) {
    $this->info('Logged in as '.$discord->user->username);

    $discord->on('message', function (Message $message) {
        $this->info("Recieved a message from {$message->author->username}: {$message->content}");
    });
});

DiscordPHP::run();
```

https://github.com/discord-php/DiscordPHP

## RestCord

```php
use Revolution\DiscordManager\Facades\RestCord;

RestCord::channel()->createMessage([
  'channel.id' => 0,
  'content' => 'test',
]);
```

https://github.com/restcord/restcord

## LICENSE
MIT  
