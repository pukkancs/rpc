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
class ApiTest extends test
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

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Authentication failed
     * @expectedExceptionCode 401
     */
    public function testNotAuthenticated()
    {
        $this->auth = false;
        $this->executeAction([self::class, 'fakeAction']);
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Params must be an array
     * @expectedExceptionCode 422
     */
    public function testWrongParamas()
    {
        $this->params = 'dsdsd';
        $this->executeAction([self::class, 'fakeAction']);
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Non existing action
     * @expectedExceptionCode 400
     */
    public function testNonExistingAction()
    {
        $this->getAction('sss');
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Method not found
     * @expectedExceptionCode 400
     */
    public function testMethodNotFound()
    {
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
