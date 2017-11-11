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

use PHPUnit\Framework\TestCase;
use Carbon\Carbon;
use PayBreak\Rpc\Validation;

/**
 * ValidationTest
 *
 * @author WN
 */
class ValidationTest extends TestCase
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
        $this->assertSame(1, Validation::paramExists('xxx', ['xxx' => 1]));
        $this->assertSame('', Validation::paramExists('xxx', ['xxx' => '']));
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Param xxx is missing
     */
    public function testFalseParamExists()
    {

        Validation::paramExists('xxx', []);
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Testing
     */
    public function testMessageParamExists()
    {
        Validation::paramExists('xxx', [], 'Testing');
    }

    public function testParamExistsAndNotEmpty()
    {
        $this->assertSame(1, Validation::paramExistsAndNotEmpty('xxx', ['xxx' => 1]));
        $this->assertSame(0, Validation::paramExistsAndNotEmpty('xxx', ['xxx' => 0]));
        $this->assertSame(false, Validation::paramExistsAndNotEmpty('xxx', ['xxx' => false]));
        $this->assertSame(null, Validation::paramExistsAndNotEmpty('xxx', ['xxx' => null]));
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Param xxx is missing
     */
    public function testFalseParamExistsAndNotEmpty()
    {
        Validation::paramExistsAndNotEmpty('xxx', []);
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Param xxx is empty
     */
    public function testEmptyParamExistsAndNotEmpty()
    {
        Validation::paramExistsAndNotEmpty('xxx', ['xxx' => '']);
    }

    public function testParamExistsAndIsArray()
    {
        $this->assertSame([], Validation::paramExistsAndIsArray('xxx', ['xxx' => []]));
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Param xxx is missing
     */
    public function testNotParamExistsAndIsArray()
    {
        Validation::paramExistsAndIsArray('xxx', []);
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Param xxx is not an array
     */
    public function testFalseParamExistsAndIsArray()
    {
        Validation::paramExistsAndIsArray('xxx', ['xxx' => 123]);
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Testing
     */
    public function testMessageFalseParamExistsAndIsArray()
    {
        Validation::paramExistsAndIsArray('xxx', ['xxx' => 123], 'Testing');
    }

    public function testParamExistsAndNotEmptyArray()
    {
        $this->assertSame([123], Validation::paramExistsAndNotEmptyArray('xxx', ['xxx' => [123]]));
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Param xxx is an empty array
     */
    public function testFalseParamExistsAndNotEmptyArray()
    {
        Validation::paramExistsAndNotEmptyArray('xxx', ['xxx' => []]);
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Testing
     */
    public function testMessageFalseParamExistsAndNotEmptyArray()
    {
        Validation::paramExistsAndNotEmptyArray('xxx', ['xxx' => []], 'Testing');
    }

    public function testParamIsNumeric()
    {
        $this->assertSame(123, Validation::paramIsNumeric('c', ['c' => 123]));
        $this->assertSame(123.01, Validation::paramIsNumeric('c', ['c' => 123.01]));
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Param c must be numeric
     * @expectedExceptionCode 422
     */
    public function testFalseParamIsNumeric()
    {
        Validation::paramIsNumeric('c', ['c' => 'xxx']);
    }

    public function testProcessDateParam()
    {
        $this->assertInstanceOf('Carbon\Carbon', Validation::processDateParam('a', ['a' => 'today']));
        $this->assertInstanceOf('Carbon\Carbon', Validation::processDateParam('a', ['a' => '2016-01-01 12:03']));
        $this->assertInstanceOf('Carbon\Carbon', Validation::processDateParam('a', ['a' => '1234556789']));
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Param a is in a wrong format
     * @expectedExceptionCode 422
     */
    public function testWrongFormatProcessDateParam()
    {
        Validation::processDateParam('a', ['a' => 123]);
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Param a is not parsable date
     * @expectedExceptionCode 422
     */
    public function testWrongDateProcessDateParam()
    {
          Validation::processDateParam('a', ['a' => 'ds fs df 123']);
    }

    public function testDefaultProcessDateParam()
    {
        $date = new Carbon();

        $this->assertSame($date, Validation::processDateParam('a', [], $date));
    }

    public function testNoDefaultProcessDateParam()
    {
        $date = new Carbon();

        $this->assertSame(
            Carbon::now()->format('Y-m-d'),
            Validation::processDateParam('a', ['a' => 'today'], $date->subDays(100))->format('Y-m-d')
        );
    }

    /**
     * @expectedException \PayBreak\Rpc\ApiException
     * @expectedExceptionMessage Param a is missing
     * @expectedExceptionCode 422
     */
    public function testMissingProcessDateParam()
    {
        Validation::processDateParam('a', []);
    }
}
