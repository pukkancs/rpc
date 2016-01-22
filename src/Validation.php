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

use Carbon\Carbon;

/**
 * Validation
 *
 * @author WN
 * @package PayBreak\Bacs\Helper
 */
class Validation
{
    /**
     * @author WN
     * @param string $param
     * @param array $params
     * @return bool
     */
    public static function checkParamExistsAndNotEmpty($param, array $params)
    {
        return (bool) (array_key_exists($param, $params) && $params[$param] !== '');
    }

    /**
     * @author WN
     * @param string $param
     * @param array $params
     * @param string|null $message
     * @return true
     * @throws ApiException
     */
    public static function paramExists($param, array $params, $message = null)
    {
        if (!array_key_exists($param, $params)) {

            throw new ApiException(($message == null)?'Param ' . $param . ' is missing':$message, 422);
        }

        return true;
    }

    /**
     * @author WN
     * @param string $param
     * @param array $params
     * @param string|null $message
     * @return true
     * @throws ApiException
     */
    public static function paramExistsAndNotEmpty($param, array $params, $message = null)
    {
        self::paramExists($param, $params);

        if (!self::checkParamExistsAndNotEmpty($param, $params)) {

            throw new ApiException(($message == null)?'Param ' . $param . ' is empty':$message, 422);
        }

        return true;
    }

    /**
     * @param string $param
     * @param array $params
     * @param string|null $message
     * @return bool
     * @throws ApiException
     */
    public static function paramExistsAndIsArray($param, array $params, $message = null)
    {
        self::paramExistsAndNotEmpty($param, $params);

        if (!is_array($params[$param])) {

            throw new ApiException(($message == null)?'Param ' . $param . ' is not an array':$message, 422);
        }

        return true;
    }

    /**
     * @param string $param
     * @param array $params
     * @param string|null $message
     * @return bool
     * @throws ApiException
     */
    public static function paramExistsAndNotEmptyArray($param, array $params, $message = null)
    {
        self::paramExistsAndIsArray($param, $params);

        if (count($params[$param]) == 0) {

            throw new ApiException(($message == null)?'Param ' . $param . ' is an empty array':$message, 422);
        }

        return true;
    }

    /**
     * @author WN
     * @param string $param
     * @param array $params
     * @return null
     * @throws ApiException
     */
    public static function paramIsNumeric($param, array $params)
    {

        self::paramExistsAndNotEmpty($param, $params);

        if (!is_numeric($params[$param])) {

            throw new ApiException('Wrong format of param: ' . $param, 422);
        }

        return $params[$param];
    }

    /**
     * @author WN
     * @param string $param
     * @param array $params
     * @return Carbon
     * @throws ApiException
     */
    public static function processDateParam($param, array $params)
    {
        self::paramExistsAndNotEmpty($param, $params);

        if (!is_string($params[$param])) {

            throw new ApiException('Wrong format of param: ' . $param, 422);
        }

        $date = Carbon::parse($params[$param]);

        if (!($date instanceof Carbon)) {

            throw new ApiException('Wrong date format: ' . $param, 422);
        }

        return $date;
    }
}
