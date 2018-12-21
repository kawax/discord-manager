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

## LICENSE
MIT  
Copyright kawax
