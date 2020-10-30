# Discord Manager

[![Build Status](https://travis-ci.com/kawax/discord-manager.svg?branch=master)](https://travis-ci.com/kawax/discord-manager)
[![Maintainability](https://api.codeclimate.com/v1/badges/27e52e9ba3df10623fae/maintainability)](https://codeclimate.com/github/kawax/discord-manager/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/27e52e9ba3df10623fae/test_coverage)](https://codeclimate.com/github/kawax/discord-manager/test_coverage)

- https://github.com/kawax/discord-project
- https://github.com/kawax/arty

## Requirements
- PHP >= 7.2
- Laravel >= 6.0 or other illuminate base project

## Installation

```
composer require revolution/discord-manager
```

### config/services.php
```php
use CharlotteDunois\Yasmin\WebSocket\Intents;

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
        'bot'       => env('DISCORD_BOT'),
        'yasmin'    => [
            'ws.disabledEvents' => [
                'TYPING_START',
            ],
            'intents'           => array_sum(Intents::default()),
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

## make command
```
php artisan make:discord:command NewChannelCommand
php artisan make:discord:direct NewDmCommand
```

## Yasmin
```php
use Revolution\DiscordManager\Facades\Yasmin;
use CharlotteDunois\Yasmin\Models\Message;


Yasmin::on('ready', function () {
    $this->info(Yasmin::user()->tag);
});

Yasmin::on('message', function (Message $message) {

});

Yasmin::login('token');
Yasmin::getLoop()->run();
```

- https://github.com/CharlotteDunois/Yasmin
- https://github.com/laravel-discord/Yasmin (forked)

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
Copyright kawax
