<?php

/*
 * This file is part of EventSource.
 *
 * (c) Igor Wiedler <igor@wiedler.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Igorw\Tests\EventSource;

use Igorw\EventSource\Event;

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialFormattedValuesShouldBeEmpty()
    {
        $event = new Event();
        $this->assertSame('', $event->getFormattedComments());
        $this->assertSame('', $event->getFormattedId());
        $this->assertSame('', $event->getFormattedEvent());
        $this->assertSame('', $event->getFormattedData());
    }

    public function testCommentFormatting()
    {
        $event = new Event();
        $event->addComment('a comment');
        $this->assertSame(": a comment\n", $event->getFormattedComments());
    }

    public function testCommentFormattingWithManyComments()
    {
        $event = new Event();
        $event->addComment('a comment');
        $event->addComment('a second comment');
        $event->addComment('another comment');
        $this->assertSame(": a comment\n: a second comment\n: another comment\n", $event->getFormattedComments());
    }

    public function testIdFormatting()
    {
        $event = new Event();
        $event->setId('1');
        $this->assertSame("id: 1\n", $event->getFormattedId());
    }

    public function testIdOverride()
    {
        $event = new Event();
        $event->setId('1');
        $this->assertSame("id: 1\n", $event->getFormattedId());
        $event->setId('2');
        $this->assertSame("id: 2\n", $event->getFormattedId());
    }

    public function testEventFormatting()
    {
        $event = new Event();
        $event->setEvent('foo');
        $this->assertSame("event: foo\n", $event->getFormattedEvent());
    }

    public function testEventOverride()
    {
        $event = new Event();
        $event->setEvent('foo');
        $this->assertSame("event: foo\n", $event->getFormattedEvent());
        $event->setEvent('bar');
        $this->assertSame("event: bar\n", $event->getFormattedEvent());
    }

    public function testDataFormatting()
    {
        $event = new Event();
        $event->setData('happy new year');
        $this->assertSame("data: happy new year\n", $event->getFormattedData());
    }

    public function testDataFormattingWithManyLines()
    {
        $event = new Event();
        $event->setData("we wish you a merry christmas\nand a happy new year");
        $this->assertSame("data: we wish you a merry christmas\ndata: and a happy new year\n", $event->getFormattedData());
    }

    public function testDataFormattingWithAppend()
    {
        $event = new Event();
        $event->appendData('we wish you a merry christmas');
        $event->appendData('and a happy new year');
        $this->assertSame("data: we wish you a merry christmas\ndata: and a happy new year\n", $event->getFormattedData());
    }

    public function testDumpIncludesEverything()
    {
        $event = new Event();
        $event->addComment('a juicy comment');
        $event->setId('11');
        $event->setEvent('foo');
        $event->setData("we wish you a merry christmas\nand a happy new year");

        $expected = <<<EOT
: a juicy comment
id: 11
event: foo
data: we wish you a merry christmas
data: and a happy new year


EOT;
        $this->assertSame($expected, $event->dump());
    }
}
