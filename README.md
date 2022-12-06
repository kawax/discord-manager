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
        'prefix'    => '/',
        'not_found' => 'Command Not Found!',
        'path'      => [
            'commands' => app_path('Discord/Commands'),
            'directs'  => app_path('Discord/Directs'),
        ],
        'token'     => env('DISCORD_BOT_TOKEN'),
        'channel'   => env('DISCORD_CHANNEL'),
        //APPLICATION ID
        'bot'       => env('DISCORD_BOT'),
        //PUBLIC KEY
        'public_key' => env('DISCORD_PUBLIC_KEY'),
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
DISCORD_CHANNEL=
DISCORD_BOT=
```

## make Discord command
```
php artisan make:discord:command NewChannelCommand
php artisan make:discord:direct NewDmCommand
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

https://github.com/teamreflex/DiscordPHP

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
