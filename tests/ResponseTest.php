<?php
/*
* This file is part of the PayBreak/basket package.
*
* (c) PayBreak <dev@paybreak.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace PayBreak\Rpc\Test;

use PHPUnit\Framework\TestCase;
use AspectMock\Test as test;
use PayBreak\Rpc\Response;

/**
 * Response Test
 *
 * @author WN
 * @package PayBreak\Rpc\Test
 */
class ResponseTest extends TestCase
{
    protected function tearDown()
    {
        test::clean(); // remove all registered test doubles
        parent::tearDown();
    }

    public function testJson()
    {
        $header = test::func('PayBreak\Rpc', 'header', true);
        ob_start();
        Response::sendJson(['xxx' => 'ccc']);
        $this->assertSame('{"xxx":"ccc"}', ob_get_clean());
        $header->verifyInvokedMultipleTimes(3);
    }
}
