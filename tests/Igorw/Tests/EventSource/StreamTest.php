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

use Igorw\EventSource\Stream;

class StreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Igorw\EventSource\Stream::__construct
     * @covers Igorw\EventSource\Stream::event
     */
    public function testEventReturnsEventWrapper()
    {
        $handler = function () {};
        $stream = new Stream($handler);
        $this->assertInstanceOf('Igorw\EventSource\EventWrapper', $stream->event());
    }

    /**
     * @covers Igorw\EventSource\Stream::__construct
     * @covers Igorw\EventSource\Stream::event
     */
    public function testEventWrapperGetsSource()
    {
        $handler = function () {};
        $stream = new Stream($handler);
        $wrapper = $stream->event();
        $this->assertSame($stream, $wrapper->end());
    }

    /**
     * @covers Igorw\EventSource\Stream::__construct
     * @covers Igorw\EventSource\Stream::flush
     */
    public function testFlushCallsHandler()
    {
        $i = 0;

        $handler = function ($chunk) use (&$i) {
            $i++;
        };

        $stream = new Stream($handler);
        $stream->event()
            ->setData('new year is over since one hour and 44 minutes');

        $this->assertSame(0, $i);

        $stream->flush();

        $this->assertSame(1, $i);
    }

    /**
     * @covers Igorw\EventSource\Stream::__construct
     * @covers Igorw\EventSource\Stream::flush
     */
    public function testHandlerGetsData()
    {
        $that = $this;

        $handler = function ($chunk) use ($that) {
            $that->assertContains('new year is over since one hour and 48 minutes', $chunk);
        };

        $stream = new Stream($handler);
        $stream->event()
            ->setData('new year is over since one hour and 48 minutes');

        $stream->flush();
    }

    /**
     * @covers Igorw\EventSource\Stream::__construct
     * @covers Igorw\EventSource\Stream::flush
     */
    public function testFlushWithoutEvents()
    {
        $that = $this;

        $handler = function ($chunk) use ($that) {
            $that->fail('Handler was invoked although no events given');
        };

        $stream = new Stream($handler);
        $stream->flush();
    }

    /**
     * @covers Igorw\EventSource\Stream::__construct
     * @covers Igorw\EventSource\Stream::getHandler
     */
    public function testGetHandler()
    {
        $handler = function ($chunk) {};
        $stream = new Stream($handler);
        $this->assertSame($handler, $stream->getHandler());
    }

    /**
     * @covers Igorw\EventSource\Stream::__construct
     * @covers Igorw\EventSource\Stream::getHandler
     */
    public function testDefaultEchoHandler()
    {
        $stream = new Stream();
        $this->assertInstanceOf('Igorw\EventSource\EchoHandler', $stream->getHandler());
    }

    /**
     * @covers Igorw\EventSource\Stream::__construct
     * @covers Igorw\EventSource\Stream::getHeaders
     */
    public function testGetHeaders()
    {
        $headers = Stream::getHeaders();
        $this->assertInternalType('array', $headers);
        $this->assertSame('text/event-stream', $headers['Content-Type']);
    }
}
