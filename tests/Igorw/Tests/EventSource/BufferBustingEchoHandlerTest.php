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

use Igorw\EventSource\BufferBustingEchoHandler;

class BufferBustingEchoHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Igorw\EventSource\BufferBustingEchoHandler
     */
    public function testInvoke()
    {
        $handler = new BufferBustingEchoHandler(10);

        ob_start();
        $handler('test string');
        $output = ob_get_clean();

        $this->expectOutputString("          \ntest string", $output);
    }
}
