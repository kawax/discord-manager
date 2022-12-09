<?php

namespace Tests;

use Discord\InteractionType;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Mockery as m;
use Discord\Interaction;
use Discord\InteractionResponseType;
use Discord\Parts\Channel\Message;
use Illuminate\Support\Facades\Event;
use Revolution\DiscordManager\Contracts\Factory;
use Revolution\DiscordManager\Contracts\InteractionsEvent;
use Revolution\DiscordManager\Contracts\InteractionsResponse;
use Revolution\DiscordManager\DiscordManager;
use Revolution\DiscordManager\Events\InteractionsWebhook;
use Revolution\DiscordManager\Exceptions\CommandNotFountException;
use Revolution\DiscordManager\Facades\DiscordManager as DiscordManagerFacade;
use Revolution\DiscordManager\Facades\RestCord;
use Revolution\DiscordManager\Http\Response\PongResponse;
use Revolution\DiscordManager\Support\Intents;
use Tests\Discord\Interactions\HelloCommand;

class DiscordTest extends TestCase
{
    public function testInstance()
    {
        $manager = new DiscordManager([]);

        $this->assertInstanceOf(DiscordManager::class, $manager);
    }

    public function testContainer()
    {
        $manager = app(Factory::class);

        $this->assertInstanceOf(DiscordManager::class, $manager);
    }

    public function testCommand()
    {
        $manager = app(Factory::class);
        $manager->add('Tests\Discord\Commands\TestCommand', DiscordManager::COMMANDS);

        $message = $this->mock(Message::class, function ($mock) {
            $mock->shouldReceive('getContentAttribute')
                 ->twice()
                 ->andReturn('/test');
            $mock->shouldReceive('reply->done')
                 ->once();
        });

        $manager->command($message);
    }

    public function testHiddenCommand()
    {
        $this->expectException(CommandNotFountException::class);

        $manager = app(Factory::class);
        $manager->add('Tests\Discord\Commands\HiddenCommand', DiscordManager::COMMANDS);

        $message = $this->mock(Message::class, function ($mock) {
            $mock->shouldReceive('getContentAttribute')
                 ->twice()
                 ->andReturn('/hide');
            $mock->shouldReceive('reply')
                 ->never();
        });

        $manager->command($message);
    }

    public function testDmCommand()
    {
        $manager = app(Factory::class);
        $manager->add('Tests\Discord\Directs\DmTestCommand', DiscordManager::DIRECTS);

        $message = $this->mock(Message::class, function ($mock) {
            $mock->shouldReceive('getContentAttribute')
                 ->twice()
                 ->andReturn('/test');
            $mock->shouldReceive('reply->done')
                 ->once();
        });

        $manager->direct($message);
    }

    public function testCommandNotFound()
    {
        $this->expectException(CommandNotFountException::class);

        $message = $this->mock(Message::class, function ($mock) {
            $mock->shouldReceive('getContentAttribute')
                 ->twice()
                 ->andReturn('/test');
            $mock->shouldReceive('reply')
                 ->never();
        });

        DiscordManagerFacade::command($message);
    }

    public function testArgvCommand()
    {
        $manager = app(Factory::class);
        $manager->add('Tests\Discord\Commands\ArgvCommand', DiscordManager::COMMANDS);

        $message = $this->mock(Message::class, function ($mock) {
            $mock->shouldReceive('getContentAttribute')
                 ->times(3)
                 ->andReturn('/argv test --option=test');
            $mock->shouldReceive('reply->done')
                 ->once();
        });

        $manager->command($message);
    }

    public function testRestCord()
    {
        $channel = RestCord::channel();

        $this->assertNotNull($channel);
    }

    public function testRestCordFail()
    {
        $this->expectException(\BadMethodCallException::class);

        $channel = RestCord::channels();
    }

    public function testIntents()
    {
        $this->assertIsArray(Intents::all());
        $this->assertIsArray(Intents::default());
        $this->assertArrayHasKey(Intents::GUILD_MESSAGES, Intents::only([Intents::GUILD_MESSAGES]));
        $this->assertArrayNotHasKey(Intents::GUILD_PRESENCES, Intents::except([Intents::GUILD_PRESENCES]));
        $this->assertSame('11011011111101', decbin(Intents::bit(Intents::default())));
        $this->assertSame('100000000000000', decbin(Intents::bit(Intents::only([Intents::DIRECT_MESSAGE_TYPING]))));
        $this->assertSame('111111111111111', decbin(array_sum(Intents::all())));
    }

    public function testInteractionsDeferred()
    {
        Event::fake();

        $response = $this->withoutMiddleware()->post(route('discord.webhook'));

        $response->assertSuccessful()
                 ->assertExactJson([
                     'type' => InteractionResponseType::DEFERRED_CHANNEL_MESSAGE_WITH_SOURCE,
                 ]);

        Event::assertDispatched(InteractionsWebhook::class);
    }

    public function testInteractionsValidateSignature()
    {
        Event::fake();

        $mock = m::mock('overload:'.Interaction::class);
        $mock->shouldReceive('verifyKey')->once()->andReturnTrue();

        $response = $this->withHeaders([
            'X-Signature-Ed25519' => 'test',
            'X-Signature-Timestamp' => 'test',
        ])->postJson(route('discord.webhook'), [
            'type' => InteractionType::PING,
        ]);

        $response->assertSuccessful()
                 ->assertExactJson([
                     'type' => InteractionResponseType::PONG,
                 ]);

        Event::assertNotDispatched(InteractionsEvent::class);
    }

    public function testInteractionsCommand()
    {
        Http::fake();

        $manager = app(Factory::class);
        $manager->add(HelloCommand::class, DiscordManager::INTERACTIONS);

        $request = Request::create(uri: 'test', method: 'POST', content: json_encode([
            'data' => [
                'name' => 'hello'
            ]
        ]));

        /** @var Response $response */
        $response = $manager->interaction($request);

        $this->assertSame(200, $response->status());
    }
}
