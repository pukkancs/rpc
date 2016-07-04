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

use Carbon\Carbon;
use JMS\Serializer\Tests\Fixtures\Discriminator\Car;
use PayBreak\Rpc\Validation;

/**
 * ValidationTest
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
        $this->assertSame(1, Validation::paramExists('xxx', ['xxx' => 1]));
        $this->assertSame('', Validation::paramExists('xxx', ['xxx' => '']));
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
        $this->assertSame(1, Validation::paramExistsAndNotEmpty('xxx', ['xxx' => 1]));
        $this->assertSame(0, Validation::paramExistsAndNotEmpty('xxx', ['xxx' => 0]));
        $this->assertSame(false, Validation::paramExistsAndNotEmpty('xxx', ['xxx' => false]));
        $this->assertSame(null, Validation::paramExistsAndNotEmpty('xxx', ['xxx' => null]));
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
        $this->assertSame([], Validation::paramExistsAndIsArray('xxx', ['xxx' => []]));
    }

    public function testNotParamExistsAndIsArray()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Param xxx is missing');
        Validation::paramExistsAndIsArray('xxx', []);
    }

    public function testFalseParamExistsAndIsArray()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Param xxx is not an array');
        Validation::paramExistsAndIsArray('xxx', ['xxx' => 123]);
    }

    public function testMessageFalseParamExistsAndIsArray()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Testing');
        Validation::paramExistsAndIsArray('xxx', ['xxx' => 123], 'Testing');
    }

    public function testParamExistsAndNotEmptyArray()
    {
        $this->assertSame([123], Validation::paramExistsAndNotEmptyArray('xxx', ['xxx' => [123]]));
    }

    public function testFalseParamExistsAndNotEmptyArray()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Param xxx is an empty array');
        Validation::paramExistsAndNotEmptyArray('xxx', ['xxx' => []]);
    }

    public function testMessageFalseParamExistsAndNotEmptyArray()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Testing');
        Validation::paramExistsAndNotEmptyArray('xxx', ['xxx' => []], 'Testing');
    }

    public function testParamIsNumeric()
    {
        $this->assertSame(123, Validation::paramIsNumeric('c', ['c' => 123]));
        $this->assertSame(123.01, Validation::paramIsNumeric('c', ['c' => 123.01]));
    }

    public function testFalseParamIsNumeric()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Param c must be numeric', 422);
        Validation::paramIsNumeric('c', ['c' => 'xxx']);
    }

    public function testProcessDateParam()
    {
        $this->assertInstanceOf('Carbon\Carbon', Validation::processDateParam('a', ['a' => 'today']));
        $this->assertInstanceOf('Carbon\Carbon', Validation::processDateParam('a', ['a' => '2016-01-01 12:03']));
        $this->assertInstanceOf('Carbon\Carbon', Validation::processDateParam('a', ['a' => '1234556789']));
    }

    public function testWrongFormatProcessDateParam()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Param a is in a wrong format', 422);
        Validation::processDateParam('a', ['a' => 123]);
    }

    public function testWrongDateProcessDateParam()
    {
        $this->setExpectedException('PayBreak\Rpc\ApiException', 'Param a is not parsable date', 422);
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
}
