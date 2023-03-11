<?php

declare(strict_types=1);

namespace Tests;

use Discord\Interaction;
use Discord\InteractionResponseType;
use Discord\InteractionType;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Mockery as m;
use Revolution\DiscordManager\Contracts\Factory;
use Revolution\DiscordManager\Contracts\InteractionsEvent;
use Revolution\DiscordManager\DiscordManager;
use Revolution\DiscordManager\Events\InteractionsWebhook;
use Revolution\DiscordManager\Exceptions\CommandNotFountException;
use Revolution\DiscordManager\Http\Middleware\ValidateSignature;
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

    public function testInteractionsDeferred()
    {
        Event::fake();

        $response = $this->withoutMiddleware(ValidateSignature::class)->post(route('discord.webhook'));

        $response->assertSuccessful()
                 ->assertExactJson([
                     'type' => InteractionResponseType::DEFERRED_CHANNEL_MESSAGE_WITH_SOURCE,
                 ]);

        Event::assertDispatched(InteractionsWebhook::class);
    }

    public function testValidateFailed()
    {
        Event::fake();

        $response = $this->post(route('discord.webhook'));

        $response->assertStatus(401);

        Event::assertNotDispatched(InteractionsWebhook::class);
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
        $manager->add(HelloCommand::class);

        $request = Request::create(uri: 'test', method: 'POST', content: json_encode([
            'token' => 'test',
            'data' => [
                'name' => 'hello',
            ],
        ]));

        /** @var Response $response */
        $response = $manager->interaction($request);

        $this->assertSame(200, $response->status());
    }

    public function testInteractionsCommandNotFound()
    {
        $this->expectException(CommandNotFountException::class);

        Http::fake();

        $request = Request::create(uri: 'test', method: 'POST', content: json_encode([
            'token' => 'test',
            'data' => [
                'name' => 'test',
            ],
        ]));

        \Revolution\DiscordManager\Facades\DiscordManager::interaction($request);
    }

    public function testInteractionsMakeCommand()
    {
        File::delete(app_path('Discord/Interactions/Test.php'));

        $this->artisan('discord:make:interaction', ['name' => 'Test'])
             ->assertSuccessful();

        $this->assertTrue(File::exists(app_path('Discord/Interactions/Test.php')));
    }

    public function testInteractionsRegister()
    {
        Http::fake();

        $this->artisan('discord:interactions:register')
             ->assertSuccessful();

        Http::assertSentCount(2);
    }
}
