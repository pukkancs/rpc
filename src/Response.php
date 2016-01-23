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
 * Response
 *
 * @author WN
 * @package PayBreak\Rpc
 */
class Response
{
    /**
     * @author WN
     * @param array $body
     * @param int $code
     */
    public static function sendJson(array $body, $code = 200)
    {
        header('HTTP/1.1 ' . $code);
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        echo json_encode($body);
    }
}
