<?php

namespace Tests;

use Discord\Parts\Channel\Message;
use Mockery as m;
use Revolution\DiscordManager\Contracts\Factory;
use Revolution\DiscordManager\DiscordManager;
use Revolution\DiscordManager\Exceptions\CommandNotFountException;
use Revolution\DiscordManager\Facades\DiscordManager as DiscordManagerFacade;
use Revolution\DiscordManager\Facades\DiscordPHP;
use Revolution\DiscordManager\Facades\RestCord;
use Revolution\DiscordManager\Facades\Yasmin;
use Revolution\DiscordManager\Support\Intents;

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
        $manager->add('Tests\Discord\Commands\TestCommand', $manager::COMMANDS);

        $message = m::mock('overload:'.Message::class);
        $message->author = (object) [
            'username' => 'test_user',
        ];
        $message->content = '/test';
        $message->shouldReceive('reply')->once()->with('test! test_user');

        $manager->command($message);
    }

    public function testHiddenCommand()
    {
        $this->expectException(CommandNotFountException::class);

        $manager = app(Factory::class);
        $manager->add('Tests\Discord\Commands\HiddenCommand', $manager::COMMANDS);

        $message = m::mock('overload:'.Message::class);
        $message->content = '/hide';
        $message->shouldReceive('reply')->never();

        $manager->command($message);
    }

    public function testDmCommand()
    {
        $manager = app(Factory::class);
        $manager->add('Tests\Discord\Directs\DmTestCommand', $manager::DIRECTS);

        $message = m::mock('overload:'.Message::class);
        $message->author = (object) [
            'username' => 'test_user',
        ];
        $message->content = '/test';
        $message->shouldReceive('reply')->once()->with('dm test! test_user');

        $manager->direct($message);
    }

    public function testCommandNotFound()
    {
        $this->expectException(CommandNotFountException::class);

        $message = m::mock('overload:'.Message::class);
        $message->content = '/test';
        $message->shouldReceive('reply')->never();

        DiscordManagerFacade::command($message);
    }

    public function testArgvCommand()
    {
        $manager = app(Factory::class);
        $manager->add('Tests\Discord\Commands\ArgvCommand', $manager::COMMANDS);

        $message = m::mock('overload:'.Message::class);
        $message->content = '/argv test --option=test';
        $message->shouldReceive('reply')->once()->with('argv! test test');

        $manager->command($message);
    }

    public function testYasmin()
    {
        $loop = Yasmin::loop();

        $this->assertNotNull($loop);
    }

    public function testYasminOn()
    {
        $this->expectNotToPerformAssertions();

        Yasmin::on('message', function () {
        });
    }

    public function testYasminFail()
    {
        $this->expectException(\BadMethodCallException::class);

        $loop = Yasmin::loops();
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

    public function testDiscordPHP()
    {
        $this->assertIsArray(DiscordPHP::__debugInfo());
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
}
