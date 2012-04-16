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

class EventWrapper
{
    private $event;
    private $source;

    public function __construct(Event $event, \Closure $source = null)
    {
        $this->event = $event;
        $this->source = $source;
    }

    public function getWrappedEvent()
    {
        return $this->event;
    }

    public function end()
    {
        if ($this->source) {
            return call_user_func($this->source);
        }
    }

    public function __call($name, $args)
    {
        if (!method_exists($this->event, $name)) {
            $message = "Could not call non-existent method '$name' on wrapped event.\n";
            $message .= 'Must be one of: '.implode(', ', get_class_methods('Igorw\EventSource\Event'));
            throw new \InvalidArgumentException($message);
        }

        $method = array($this->event, $name);
        $value = call_user_func_array($method, $args);

        if ($this->event === $value) {
            return $this;
        }

        return $value;
    }
}
