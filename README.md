# Discord Manager

- https://github.com/kawax/discord-project
- https://github.com/kawax/arty

## Requirements
- PHP >= 7.1.3
- Laravel >= 5.7 or other illuminate base project

## Installation

```
composer require revolution/discord-manager
```

### config/services.php
```php
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
        ],
    ],
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

```

https://github.com/CharlotteDunois/Yasmin

## RestCord

```php
use Revolution\DiscordManager\Facades\RestCord;

RestCord::channel()->createMessage([
  'channel.id' => 0,
  'content' => 'test,
]);
```

https://github.com/restcord/restcord

## LICENSE
MIT  
Copyright kawax
