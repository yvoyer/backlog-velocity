<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub\Mapping;

use Star\Component\Sprint\Mapping\DataSet;

/**
 * Class DataSetStub
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Mapping
 */
class DataSetStub implements DataSet
{
    /**
     * Populate the $object with data from the dataSet.
     *
     * @param object $object
     */
    public function populate($object)
    {
        $object->setName('name');
    }
}
