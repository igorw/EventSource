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
 * This handler outputs whitespace ahead of payload to break potential buffer limits from FCGI.
 */
class BufferBustingEchoHandler extends EchoHandler
{
    /**
     * @var string
     */
    private $buffer;

    /**
     * @param int $bufferSize
     */
    public function __construct($bufferSize = 4096)
    {
        $this->buffer = str_repeat(" ", $bufferSize)."\n";
    }

    public function __invoke($chunk)
    {
        echo $this->buffer;

        parent::__invoke($chunk);
    }
}
