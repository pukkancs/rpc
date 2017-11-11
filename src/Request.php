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
 * ApiRequest
 *
 * @author WN
 * @package PayBreak\Rpc
 */
final class Request
{
    private static $instance;

    private $body = [];

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return $this
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->body = $this->getRequest();
    }

    private function __clone()
    {
    }

    /**
     * @author WN
     * @return array
     */
    public static function json()
    {
        return self::getInstance()->body;
    }

    /**
     * @author WN
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public static function getParam($name, $default = null)
    {
        $instance = self::getInstance();

        if (array_key_exists($name, $instance->body)) {
            return $instance->body[$name];
        }

        if (array_key_exists($name, $_POST)) {
            return $_POST[$name];
        }

        if (array_key_exists($name, $_GET)) {
            return $_GET[$name];
        }

        return $default;
    }

    /**
     * @author WN
     * @return array
     * @throws ApiException
     */
    private function getRequest()
    {
        $request = file_get_contents('php://input');
        $body = json_decode($request, true);

        if (is_array($body)) {
            return $body;
        }

        return [];
    }
}
