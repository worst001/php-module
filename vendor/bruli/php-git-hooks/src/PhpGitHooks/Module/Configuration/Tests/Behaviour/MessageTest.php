<?php

namespace PhpGitHooks\Module\Configuration\Tests\Behaviour;

use PhpGitHooks\Module\Configuration\Domain\Message;
use PHPUnit\Framework\TestCase;
use PhpValueObjects\Scalar\Exception\InvalidStringException;

class MessageTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldThrowsException()
    {
        $this->expectException(InvalidStringException::class);

        new Message(12);
    }

    /**
     * @test
     */
    public function itShouldReturnString()
    {
        $text = 'message';
        $message = new Message($text);

        $this->assertSame($text, $message->__toString());
    }
}
