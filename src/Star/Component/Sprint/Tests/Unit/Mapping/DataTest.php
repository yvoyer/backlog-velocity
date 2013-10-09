<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Mapping;

use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class DataTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Mapping
 *
 * @covers Star\Component\Sprint\Mapping\Data
 */
class DataTest extends UnitTestCase
{
    /**
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Star\Component\Sprint\Mapping\Data
     */
    private function getDataClass()
    {
        return $this->getMockForAbstractClass('Star\Component\Sprint\Mapping\Data');
    }

    public function testShouldReturnTrueWhenNoConstraintsFound()
    {
        $dataClass = $this->getDataClass();
        $dataClass
            ->expects($this->once())
            ->method('getValidationConstraints')
            ->will($this->returnValue(new NotBlank()));
        $dataClass
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue(' '));

        $this->assertTrue($dataClass->isValid());
    }

    public function testShouldReturnFalseWhenAtLeastOneConstraintWasFound()
    {
        $dataClass = $this->getDataClass();
        $dataClass
            ->expects($this->once())
            ->method('getValidationConstraints')
            ->will($this->returnValue(new NotBlank()));
        $dataClass
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue(''));

        $this->assertFalse($dataClass->isValid());
    }
}
