<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Mapping;

use Star\Component\Sprint\Mapping\ClassMapper;
use Star\Component\Sprint\Tests\Stub\Mapping\DataSetStub;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class ClassMapperTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Mapping
 *
 * @covers Star\Component\Sprint\Mapping\ClassMapper
 */
class ClassMapperTest extends UnitTestCase
{
    public function testShouldMapADataSetToAnObject()
    {
        $objectClass  = 'Star\Component\Sprint\Tests\Stub\Mapping\ObjectStub';
        $dataSet      = new DataSetStub();
        $dataSetClass = get_class($dataSet);

        $mapper = new ClassMapper();
        $mapper->addMapping($objectClass, $dataSetClass);
        $object = $mapper->load($dataSet);

        $this->assertInstanceOf($objectClass, $object);
        $this->assertSame('name', $object->getName());
    }
}
