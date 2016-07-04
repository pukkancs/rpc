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
     * @return mixed
     * @throws ApiException
     */
    public static function paramExists($param, array $params, $message = null)
    {
        if (!array_key_exists($param, $params)) {

            throw new ApiException(($message === null)?'Param ' . $param . ' is missing':$message, 422);
        }

        return $params[$param];
    }

    /**
     * @author WN
     * @param string $param
     * @param array $params
     * @param string|null $message
     * @return mixed
     * @throws ApiException
     */
    public static function paramExistsAndNotEmpty($param, array $params, $message = null)
    {
        self::paramExists($param, $params);

        if (!self::checkParamExistsAndNotEmpty($param, $params)) {

            throw new ApiException(($message === null)?'Param ' . $param . ' is empty':$message, 422);
        }

        return $params[$param];
    }

    /**
     * @param string $param
     * @param array $params
     * @param string|null $message
     * @return array
     * @throws ApiException
     */
    public static function paramExistsAndIsArray($param, array $params, $message = null)
    {
        self::paramExistsAndNotEmpty($param, $params);

        if (!is_array($params[$param])) {

            throw new ApiException(($message === null)?'Param ' . $param . ' is not an array':$message, 422);
        }

        return $params[$param];
    }

    /**
     * @param string $param
     * @param array $params
     * @param string|null $message
     * @return array
     * @throws ApiException
     */
    public static function paramExistsAndNotEmptyArray($param, array $params, $message = null)
    {
        self::paramExistsAndIsArray($param, $params);

        if (count($params[$param]) == 0) {

            throw new ApiException(($message === null)?'Param ' . $param . ' is an empty array':$message, 422);
        }

        return $params[$param];
    }

    /**
     * @author WN
     * @param string $param
     * @param array $params
     * @return int|float
     * @throws ApiException
     */
    public static function paramIsNumeric($param, array $params)
    {

        self::paramExistsAndNotEmpty($param, $params);

        if (!is_numeric($params[$param])) {

            throw new ApiException('Param ' . $param . ' must be numeric', 422);
        }

        return $params[$param];
    }

    /**
     * @author WN
     * @param string $param
     * @param array $params
     * @param Carbon|null $default
     * @return Carbon
     * @throws ApiException
     */
    public static function processDateParam($param, array $params, Carbon $default = null)
    {
        try {
            self::paramExistsAndNotEmpty($param, $params);
        } catch (ApiException $e) {

            if ($default instanceof Carbon) {
                return $default;
            }

            throw $e;
        }

        self::isString($param, $params);

        try{
            $date = Carbon::parse($params[$param]);
        } catch (\Exception $e) {
            throw new ApiException('Param ' . $param . ' is not parsable date', 422, $e);
        }

        return $date;
    }

    /**
     * @author WN
     * @param $param
     * @param array $params
     * @throws ApiException
     */
    private static function isString($param, array $params)
    {
        if (!is_string($params[$param])) {

            throw new ApiException('Param ' . $param . ' is in a wrong format', 422);
        }
    }
}
