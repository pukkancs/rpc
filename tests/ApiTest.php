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

    protected function tearDown()
    {
        test::clean(); // remove all registered test doubles
        parent::tearDown();
    }

    protected function getActions()
    {
        return [
            'test'  => [self::class, 'fakeAction'],
            'none'  => [self::class, 'nonExisting'],
            'exc'   => [self::class, 'actionException'],
            'str'   => [self::class, 'actionString'],
        ];
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

    private function actionException(array $params)
    {
        throw new \Exception('Test 123');
    }

    private function actionString()
    {
        return 'acdf';
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

    public function testExecuteCall()
    {
        $this->params = ['aaa'];
        $this->action = 'test';
        $response = test::double('PayBreak\Rpc\Response', ['sendJson' => true]);

        $this->executeCall();

        $response->verifyInvokedOnce('sendJson', [['aaa'], 200]);
    }

    public function testExecuteErrorCall()
    {
        $this->params = ['aaa'];
        $this->action = 'test';
        $this->auth = false;
        $response = test::double('PayBreak\Rpc\Response', ['sendJson' => true]);

        $this->executeCall();

        $response->verifyInvokedOnce('sendJson', [['error' => 'Authentication failed'], 401]);
    }

    public function testExecuteExceptionCall()
    {
        $this->params = ['aaa'];
        $this->action = 'exc';
        $this->auth = true;
        $response = test::double('PayBreak\Rpc\Response', ['sendJson' => true]);

        $this->executeCall();

        $response->verifyInvokedOnce('sendJson', [['error' => 'Test 123'], 500]);
    }

    public function testWrongResponse()
    {
        $this->params = ['aaa'];
        $this->action = 'str';
        $this->auth = true;
        $response = test::double('PayBreak\Rpc\Response', ['sendJson' => true]);

        $this->executeCall();

        $response->verifyInvokedOnce('sendJson', [['error' => 'Unprocesable response'], 500]);
    }
}
