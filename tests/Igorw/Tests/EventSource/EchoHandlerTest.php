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

use Igorw\EventSource\EchoHandler;

class EchoHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Igorw\EventSource\EchoHandler
     */
    public function testInvoke()
    {
        $handler = new EchoHandler();

        ob_start();
        $handler('new year is over since two hours and 6 minutes');
        $output = ob_get_clean();

        $this->expectOutputString('new year is over since two hours and 6 minutes', $output);
    }
}
