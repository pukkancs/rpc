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

use PayBreak\Foundation\Exception;

/**
 * Api Exception
 *
 * @author WN
 * @package PayBreak\Rpc
 */
class ApiException extends Exception
{
    public function __construct($message, $code = 500, \Exception $previous = null)
    {
        \Exception::__construct($message, $code, $previous);
    }
}
