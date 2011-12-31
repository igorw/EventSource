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

class EchoHandler
{
    public function __invoke($chunk)
    {
        echo $chunk;
        ob_flush();
        flush();
    }
}
