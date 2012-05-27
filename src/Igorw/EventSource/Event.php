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

class Event
{
    private $comments = array();
    private $id;
    private $event;
    private $retry;
    private $data = array();

    public function addComment($comment)
    {
        $this->comments = array_merge(
            $this->comments,
            $this->extractNewlines($comment)
        );

        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

   public function setRetry($retry)
    {
        if (!is_numeric($retry)) {
            throw new \InvalidArgumentException('Retry value must be numeric.');
        }

        $this->retry = $retry;

        return $this;
    }

    public function setData($data)
    {
        $this->data = $this->extractNewlines($data);

        return $this;
    }

    public function appendData($data)
    {
        $this->data = array_merge(
            $this->data,
            $this->extractNewlines($data)
        );

        return $this;
    }

    public function dump()
    {
        $response = $this->getFormattedComments().
                    $this->getFormattedId().
                    $this->getFormattedEvent().
                    $this->getFormattedRetry().
                    $this->getFormattedData();

        return '' !== $response ? $response."\n" : '';
    }

    public function getFormattedComments()
    {
        return $this->formatLines('', $this->comments);
    }

    public function getFormattedId()
    {
        return $this->formatLines('id', $this->id);
    }

    public function getFormattedEvent()
    {
        return $this->formatLines('event', $this->event);
    }

    public function getFormattedRetry()
    {
        return $this->formatLines('retry', $this->retry);
    }

    public function getFormattedData()
    {
        return $this->formatLines('data', $this->data);
    }

    private function extractNewlines($input)
    {
        return explode("\n", $input);
    }

    private function formatLines($key, $lines)
    {
        $formatted = array_map(
            function ($line) use ($key) {
                return $key.': '.$line."\n";
            },
            (array) $lines
        );

        return implode('', $formatted);
    }

    static public function create()
    {
        return new static();
    }
}
