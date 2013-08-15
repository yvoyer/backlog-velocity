<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Mapping;

/**
 * Class DataSet
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Mapping
 *
 * Contract for class that contains data relevant to the instantiation of another object.
 */
interface DataSet
{
    /**
     * Populate the $object with data from the dataSet.
     *
     * @param object $object
     */
    public function populate($object);
}
