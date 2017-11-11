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

use AspectMock\Test as test;
use PayBreak\Rpc\Request;
use PHPUnit\Framework\TestCase;

/**
 * Request Test
 *
 * @author WN
 */
class RequestTest extends TestCase
{
    protected function tearDown()
    {
        test::clean(); // remove all registered test doubles
        parent::tearDown();
    }

    public function testGetParam()
    {
        $func = test::func('PayBreak\Rpc', 'file_get_contents', json_encode(['xxx' => 'yyy']));
        $this->assertSame('yyy', Request::getParam('xxx'));
        $func->verifyInvoked();
    }

    public function testJson()
    {
        $this->assertSame(['xxx' => 'yyy'], Request::json());
    }

    public function testDefaultGetParam()
    {
        $this->assertNull(Request::getParam('dsd'));
        $this->assertSame('wew', Request::getParam('dsd', 'wew'));
    }

    public function testGet()
    {
        $_GET['xs'] = 125;
        $this->assertSame(125, Request::getParam('xs'));
    }

    public function testPost()
    {
        $_POST['xy'] = 1256;
        $this->assertSame(1256, Request::getParam('xy'));
    }
}
