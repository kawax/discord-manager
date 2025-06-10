# Discord Manager

> **Note** Since v5, only Interactions command is provided. Interactions command is webhook-based, it is easy to use in Laravel.

## Overview

## Requirements
- PHP >= 8.2
- Laravel >= 11.0

## Installation

```shell
composer require revolution/discord-manager
```

### .env
```
DISCORD_BOT_TOKEN=

# APPLICATION ID
DISCORD_BOT=

# PUBLIC KEY
DISCORD_PUBLIC_KEY=

# GUILD ID
DISCORD_GUILD=
```

### Uninstall
```shell
composer remove revolution/discord-manager
```

- Delete `config/discord_interactions.php`
- Delete `app/Discord/` and other files.
- Delete `DISCORD_*` in `.env`

## Interactions
### Publish config file
```shell
php artisan vendor:publish --tag=discord-interactions-config
```

### Edit config/discord_interactions.php

### Set `INTERACTIONS ENDPOINT URL` in Discord's developer portal.
```
https://example/discord/webhook
```

### Create a command to respond
```shell
php artisan discord:make:interaction HelloCommand
```

### Register commands to Discord server

You need to define and register the commands you actually use in `config/discord_interactions.php`.

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
     */
    public function handle(InteractionsWebhook $event)
    {
        DiscordManager::interaction($event->request);
    }
```

## Workflow
1. POST request comes in from Discord to https://example/discord/webhook
2. [ValidateSignature](./src/Http/Middleware/ValidateSignature.php)
3. [InteractionsWebhookController](./src/Http/Controllers/InteractionsWebhookController.php)
4. [DeferredResponse](./src/Http/Response/DeferredResponse.php)
5. [DispatchInteractionsEvent](./src/Http/Middleware/DispatchInteractionsEvent.php) Terminable Middleware
6. [InteractionsWebhook](./src/Events/InteractionsWebhook.php) Event dispatch
7. InteractionsListener in your project.
8. DiscordManager invokes one of the commands in `app/Discord/`.

## Sample

https://github.com/kawax/discord-interactions

## LICENSE
MIT  
