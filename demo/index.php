<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 * php -S localhost:8080 index.php
 */

include_once '../vendor/autoload.php';

class MyApi
{
    use \PayBreak\Rpc\Api;

    protected function getActions()
    {
        return [
            'ping' => [self::class, 'ping'],
        ];
    }

    protected function authenticate()
    {
        return true;
    }

    protected function getRequestAction()
    {
        return \PayBreak\Rpc\Request::getParam('action');
    }

    protected function getRequestParams()
    {
        return (array) \PayBreak\Rpc\Request::getParam('params');
    }

    protected function ping(array $params)
    {
        return ['pong' => true, 'request' => $params];
    }
}


$obj = new MyApi();

$obj->executeCall();
