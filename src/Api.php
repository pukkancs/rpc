<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PayBreak\Rpc;

/**
 * Trait Api
 *
 * @author WN
 * @package PayBreak\Rpc
 */
trait Api
{
    /**
     * Configuration of available actions
     *
     * 'action_name' => callable
     *
     * @author WN
     * @return callable[]
     */
    abstract protected function getActions();

    /**
     * Indicates if request is authenticated
     *
     * @author WN
     * @return bool
     */
    abstract protected function authenticate();

    /**
     * Return name of requested action
     *
     * @author WN
     * @return string
     */
    abstract protected function getRequestAction();

    /**
     * Return params to be passed to action
     *
     * @author WN
     * @return array
     */
    abstract protected function getRequestParams();

    /**
     * Handles request and sends Response. Default implementation.
     *
     * @author WN
     */
    public function executeCall()
    {
        try {
            $result = $this->executeAction($this->getAction($this->getRequestAction()));

            if (!is_array($result)) {
                throw new ApiException('Unprocesable response', 500);
            }

            Response::sendJson($result);

        } catch (ApiException $e) {

            Response::sendJson(['error' => $e->getMessage()], $e->getCode());

        } catch (\Exception $e) {

            Response::sendJson(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get Callable of Action
     *
     * @param string $action
     * @return callable
     * @throws ApiException
     */
    protected function getAction($action)
    {
        $actions = self::getActions();

        if (!array_key_exists($action, $actions)) {

            throw new ApiException('Non existing action', 400);
        }

        if (!is_callable($actions[$action])) {
            throw new ApiException('Method not found', 400);
        }

        return $actions[$action];
    }

    /**
     * Execute Action
     *
     * Callable action must return an array or throw an exception
     *
     * @author WN
     * @param callable $action
     * @return array
     * @throws ApiException
     */
    protected function executeAction(callable $action)
    {
        if (!$this->authenticate()) {
            throw new ApiException('Authentication failed', 401);
        }

        if (!is_array($this->getRequestParams())) {
            throw new ApiException('Params must be an array', 422);
        }

        return call_user_func($action, $this->getRequestParams());
    }
}
