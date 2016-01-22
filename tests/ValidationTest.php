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

use PayBreak\Rpc\Validation;

/**
 * ValidationTest
 *
 * @todo paramIsNumeric() and processDateParam()
 *
 * @author WN
 */
class ValidationTest extends \PHPUnit_Framework_TestCase
{
    public function testTrueCheckParamExistsAndNotEmpty()
    {
        $this->assertTrue(Validation::checkParamExistsAndNotEmpty('xxx', ['xxx' => 1]));
        $this->assertTrue(Validation::checkParamExistsAndNotEmpty('xxx', ['xxx' => 0]));
        $this->assertTrue(Validation::checkParamExistsAndNotEmpty('xxx', ['xxx' => false]));
        $this->assertTrue(Validation::checkParamExistsAndNotEmpty('xxx', ['xxx' => null]));
    }

    public function testFalseCheckParamExistsAndNotEmpty()
    {
        $this->assertFalse(Validation::checkParamExistsAndNotEmpty('xxx', ['xxx' => '']));
        $this->assertFalse(Validation::checkParamExistsAndNotEmpty('xxx', []));
    }

    public function testParamExists()
    {
        $this->assertTrue(Validation::paramExists('xxx', ['xxx' => 1]));
        $this->assertTrue(Validation::paramExists('xxx', ['xxx' => '']));
    }

    public function testFalseParamExists()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Param xxx is missing');
        Validation::paramExists('xxx', []);
    }

    public function testMessageParamExists()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Testing');
        Validation::paramExists('xxx', [], 'Testing');
    }

    public function testParamExistsAndNotEmpty()
    {
        $this->assertTrue(Validation::paramExistsAndNotEmpty('xxx', ['xxx' => 1]));
        $this->assertTrue(Validation::paramExistsAndNotEmpty('xxx', ['xxx' => 0]));
        $this->assertTrue(Validation::paramExistsAndNotEmpty('xxx', ['xxx' => false]));
        $this->assertTrue(Validation::paramExistsAndNotEmpty('xxx', ['xxx' => null]));
    }

    public function testFalseParamExistsAndNotEmpty()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Param xxx is missing');
        Validation::paramExistsAndNotEmpty('xxx', []);
    }

    public function testEmptyParamExistsAndNotEmpty()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Param xxx is empty');
        Validation::paramExistsAndNotEmpty('xxx', ['xxx' => '']);
    }

    public function testParamExistsAndIsArray()
    {
        $this->assertTrue(Validation::paramExistsAndIsArray('xxx', ['xxx' => []]));
    }

    public function testNotParamExistsAndIsArray()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Param xxx is missing');
        $this->assertTrue(Validation::paramExistsAndIsArray('xxx', []));
    }

    public function testFalseParamExistsAndIsArray()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Param xxx is not an array');
        $this->assertTrue(Validation::paramExistsAndIsArray('xxx', ['xxx' => 123]));
    }

    public function testMessageFalseParamExistsAndIsArray()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Testing');
        $this->assertTrue(Validation::paramExistsAndIsArray('xxx', ['xxx' => 123], 'Testing'));
    }

    public function testParamExistsAndNotEmptyArray()
    {
        $this->assertTrue(Validation::paramExistsAndNotEmptyArray('xxx', ['xxx' => [123]]));
    }

    public function testFalseParamExistsAndNotEmptyArray()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Param xxx is an empty array');
        $this->assertTrue(Validation::paramExistsAndNotEmptyArray('xxx', ['xxx' => []]));
    }

    public function testMessageFalseParamExistsAndNotEmptyArray()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Testing');
        $this->assertTrue(Validation::paramExistsAndNotEmptyArray('xxx', ['xxx' => []], 'Testing'));
    }
}
