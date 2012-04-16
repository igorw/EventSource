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

use Igorw\EventSource\EventWrapper;

class EventWrapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Igorw\EventSource\EventWrapper::__construct
     * @covers Igorw\EventSource\EventWrapper::getWrappedEvent
     */
    public function testGetWrappedEvent()
    {
        $event = $this->getMock('Igorw\EventSource\Event');
        $wrapper = new EventWrapper($event);
        $this->assertSame($event, $wrapper->getWrappedEvent());
    }

    /**
     * @covers Igorw\EventSource\EventWrapper::__construct
     * @covers Igorw\EventSource\EventWrapper::end
     */
    public function testEnd()
    {
        $event = $this->getMock('Igorw\EventSource\Event');
        $stream = $this->getMock('Igorw\EventSource\Stream');
        $source = function () use ($stream) {
            return $stream;
        };
        $wrapper = new EventWrapper($event, $source);
        $this->assertSame($stream, $wrapper->end());
    }

    /**
     * @covers Igorw\EventSource\EventWrapper::__construct
     * @covers Igorw\EventSource\EventWrapper::end
     */
    public function testEndWithoutSource()
    {
        $event = $this->getMock('Igorw\EventSource\Event');
        $wrapper = new EventWrapper($event);
        $this->assertSame(null, $wrapper->end());
    }

    /**
     * @covers Igorw\EventSource\EventWrapper::__construct
     * @covers Igorw\EventSource\EventWrapper::__call
     */
    public function testWrappingMethodCalls()
    {
        $event = $this->getMock('Igorw\EventSource\Event');
        $event
            ->expects($this->once())
            ->method('addComment')
            ->will($this->returnValue($event));
        $event
            ->expects($this->once())
            ->method('setId')
            ->will($this->returnValue($event));
        $event
            ->expects($this->once())
            ->method('setEvent')
            ->will($this->returnValue($event));
        $event
            ->expects($this->once())
            ->method('setData')
            ->will($this->returnValue($event));

        $stream = $this->getMock('Igorw\EventSource\Stream');
        $source = function () use ($stream) {
            return $stream;
        };

        $wrapper = new EventWrapper($event, $source);
        $wrapper
            ->addComment('a comment')
            ->setId('1')
            ->setEvent('foo')
            ->setData('new year is over');
    }

    /**
     * @covers Igorw\EventSource\EventWrapper::__construct
     * @covers Igorw\EventSource\EventWrapper::__call
     */
    public function testWrappingMethodCallsWithDump()
    {
        $event = $this->getMock('Igorw\EventSource\Event');
        $event
            ->expects($this->once())
            ->method('dump')
            ->will($this->returnValue(''));

        $wrapper = new EventWrapper($event);
        $wrapper
            ->dump();
    }

    /**
     * @covers Igorw\EventSource\EventWrapper::__construct
     * @covers Igorw\EventSource\EventWrapper::__call
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Could not call non-existent method 'nonExistentMethod' on wrapped event.
     */
    public function testCallingNonExistentMethod()
    {
        $event = $this->getMock('Igorw\EventSource\Event');

        $wrapper = new EventWrapper($event);
        $wrapper
            ->nonExistentMethod();
    }
}
