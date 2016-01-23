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
use PayBreak\Rpc\Api;

/**
 * Request Test
 *
 * @author WN
 */
class TestTest extends \PHPUnit_Framework_TestCase
{
    use Api;

    private $auth = true;
    private $action = 'none';
    private $params = [];

    protected function getActions()
    {
        return ['test' => [self::class, 'fakeAction'], 'none' => [self::class, 'nonExisting']];
    }

    protected function authenticate()
    {
        return $this->auth;
    }

    protected function getRequestAction()
    {
        return $this->action;
    }

    protected function getRequestParams()
    {
        return $this->params;
    }

    private function fakeAction(array $params)
    {
        return $params;
    }

    public function testNotAuthenticated()
    {
        $this->auth = false;
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Authentication failed', 401);
        $this->executeAction([self::class, 'fakeAction']);
    }

    public function testWrongParamas()
    {
        $this->params = 'dsdsd';
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Params must be an array', 422);
        $this->executeAction([self::class, 'fakeAction']);
    }

    public function testNonExistingAction()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Non existing action', 400);
        $this->getAction('sss');
    }

    public function testMethodNotFound()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Method not found', 400);
        $this->getAction('none');
    }

    public function testGetAction()
    {
        $this->assertCount(2, $this->getAction('test'));
    }
}
