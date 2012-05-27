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
    /**
     * @covers Igorw\EventSource\Event
     */
    public function testInitialFormattedValuesShouldBeEmpty()
    {
        $event = new Event();
        $this->assertSame('', $event->getFormattedComments());
        $this->assertSame('', $event->getFormattedId());
        $this->assertSame('', $event->getFormattedEvent());
        $this->assertSame('', $event->getFormattedRetry());
        $this->assertSame('', $event->getFormattedData());
    }

    /**
     * @covers Igorw\EventSource\Event::addComment
     * @covers Igorw\EventSource\Event::getFormattedComments
     */
    public function testCommentFormatting()
    {
        $event = new Event();
        $event->addComment('a comment');
        $this->assertSame(": a comment\n", $event->getFormattedComments());
    }

    /**
     * @covers Igorw\EventSource\Event::addComment
     * @covers Igorw\EventSource\Event::getFormattedComments
     */
    public function testCommentFormattingWithManyComments()
    {
        $event = new Event();
        $event->addComment('a comment');
        $event->addComment('a second comment');
        $event->addComment('another comment');
        $this->assertSame(": a comment\n: a second comment\n: another comment\n", $event->getFormattedComments());
    }

    /**
     * @covers Igorw\EventSource\Event::setId
     * @covers Igorw\EventSource\Event::getFormattedId
     */
    public function testIdFormatting()
    {
        $event = new Event();
        $event->setId('1');
        $this->assertSame("id: 1\n", $event->getFormattedId());
    }

    /**
     * @covers Igorw\EventSource\Event::setId
     * @covers Igorw\EventSource\Event::getFormattedId
     */
    public function testIdOverride()
    {
        $event = new Event();
        $event->setId('1');
        $this->assertSame("id: 1\n", $event->getFormattedId());
        $event->setId('2');
        $this->assertSame("id: 2\n", $event->getFormattedId());
    }

    /**
     * @covers Igorw\EventSource\Event::setEvent
     * @covers Igorw\EventSource\Event::getFormattedEvent
     */
    public function testEventFormatting()
    {
        $event = new Event();
        $event->setEvent('foo');
        $this->assertSame("event: foo\n", $event->getFormattedEvent());
    }

    /**
     * @covers Igorw\EventSource\Event::setEvent
     * @covers Igorw\EventSource\Event::getFormattedEvent
     */
    public function testEventOverride()
    {
        $event = new Event();
        $event->setEvent('foo');
        $this->assertSame("event: foo\n", $event->getFormattedEvent());
        $event->setEvent('bar');
        $this->assertSame("event: bar\n", $event->getFormattedEvent());
    }

    /**
     * @covers Igorw\EventSource\Event::setRetry
     * @covers Igorw\EventSource\Event::getFormattedRetry
     */
    public function testRetryFormatting()
    {
        $event = new Event();
        $event->setRetry(1000);
        $this->assertSame("retry: 1000\n", $event->getFormattedRetry());
    }

    /**
     * @covers Igorw\EventSource\Event::setRetry
     * @covers Igorw\EventSource\Event::getFormattedRetry
     */
    public function testRetryWithZero()
    {
        $event = new Event();
        $event->setRetry(0);
        $this->assertSame("retry: 0\n", $event->getFormattedRetry());
    }

    /**
     * @covers Igorw\EventSource\Event::setRetry
     * @expectedException InvalidArgumentException
     */
    public function testRetryValueMustBeNumeric()
    {
        $event = new Event();
        $event->setRetry('not an int');
    }

    /**
     * @covers Igorw\EventSource\Event::setRetry
     * @covers Igorw\EventSource\Event::getFormattedRetry
     */
    public function testRetryOverride()
    {
        $event = new Event();
        $event->setRetry(1000);
        $this->assertSame("retry: 1000\n", $event->getFormattedRetry());
        $event->setRetry(3000);
        $this->assertSame("retry: 3000\n", $event->getFormattedRetry());
    }

    /**
     * @covers Igorw\EventSource\Event::setData
     * @covers Igorw\EventSource\Event::getFormattedData
     */
    public function testDataFormatting()
    {
        $event = new Event();
        $event->setData('happy new year');
        $this->assertSame("data: happy new year\n", $event->getFormattedData());
    }

    /**
     * @covers Igorw\EventSource\Event::setData
     * @covers Igorw\EventSource\Event::getFormattedData
     * @covers Igorw\EventSource\Event::extractNewlines
     * @covers Igorw\EventSource\Event::formatLines
     */
    public function testDataFormattingWithManyLines()
    {
        $event = new Event();
        $event->setData("we wish you a merry christmas\nand a happy new year");
        $this->assertSame("data: we wish you a merry christmas\ndata: and a happy new year\n", $event->getFormattedData());
    }

    /**
     * @covers Igorw\EventSource\Event::appendData
     * @covers Igorw\EventSource\Event::getFormattedData
     */
    public function testDataFormattingWithAppend()
    {
        $event = new Event();
        $event->appendData('we wish you a merry christmas');
        $event->appendData('and a happy new year');
        $this->assertSame("data: we wish you a merry christmas\ndata: and a happy new year\n", $event->getFormattedData());
    }

    /**
     * @covers Igorw\EventSource\Event::dump
     */
    public function testDumpIncludesEverything()
    {
        $event = new Event();
        $event->addComment('a juicy comment');
        $event->setId('11');
        $event->setEvent('foo');
        $event->setRetry(2000);
        $event->setData("we wish you a merry christmas\nand a happy new year");

        $expected = <<<EOT
: a juicy comment
id: 11
event: foo
retry: 2000
data: we wish you a merry christmas
data: and a happy new year


EOT;
        $this->assertSame($expected, $event->dump());
    }

    /**
     * @covers Igorw\EventSource\Event::create
     */
    public function testCreate()
    {
        $event = Event::create();
        $this->assertInstanceOf('Igorw\EventSource\Event', $event);
    }
}
