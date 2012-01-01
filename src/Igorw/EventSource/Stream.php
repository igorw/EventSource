<?php

/*
 * This file is part of EventSource.
 *
 * (c) Igor Wiedler <igor@wiedler.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Igorw\EventSource;

/**
 * Generates a stream in the W3C EventSource format
 * http://dev.w3.org/html5/eventsource/
 */
class Stream
{
    private $buffer;
    private $handler;

    public function __construct($handler = null)
    {
        $this->buffer = new \SplQueue();
        $this->buffer->setIteratorMode(\SplQueue::IT_MODE_DELETE);
        $this->handler = $handler ?: new EchoHandler();
    }

    public function event()
    {
        $event = new Event();
        $this->buffer->enqueue($event);

        $that = $this;

        $wrapper = new EventWrapper($event, function () use ($that) {
            return $that;
        });

        return $wrapper;
    }

    public function flush()
    {
        foreach ($this->buffer as $event) {
            $chunk = $event->dump();
            if ('' !== $chunk) {
                call_user_func($this->handler, $chunk);
            }
        }
    }

    public function getHandler()
    {
        return $this->handler;
    }

    static public function getHeaders()
    {
        return array(
            'Content-Type'  => 'text/event-stream',
            'Transfer-Encoding' => 'identity',
            'Cache-Control' => 'no-cache',
        );
    }
}
