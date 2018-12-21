<?php

namespace Tests;

use Mockery as m;
use Revolution\DiscordManager\DiscordManager;
use Revolution\DiscordManager\Contracts\Factory;
use Revolution\DiscordManager\Facades\DiscordManager as DiscordManagerFacade;

use CharlotteDunois\Yasmin\Models\Message;

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

        $message = m::mock('overload:' . Message::class);
        $message->author = new class
        {
            public $username = 'test_user';
        };
        $message->content = '/test';

        $reply = $manager->command($message);

        $this->assertSame('test! test_user', $reply);
    }

    public function testDmCommand()
    {
        $manager = app(Factory::class);
        $manager->add('Tests\Discord\Directs\DmTestCommand', $manager::DIRECTS);

        $message = m::mock('overload:' . Message::class);
        $message->author = new class
        {
            public $username = 'test_user';
        };
        $message->content = '/test';

        $reply = $manager->direct($message);

        $this->assertSame('dm test! test_user', $reply);
    }

    public function testCommandNotFound()
    {
        $message = m::mock('overload:' . Message::class);
        $message->content = '/test';

        $reply = DiscordManagerFacade::command($message);

        $this->assertSame('Command Not Found!', $reply);
    }
}
