<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Mapping;

/**
 * Class ClassMapper
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Mapping
 */
class ClassMapper
{
    /**
     * @var array
     */
    private $mappings = array();

    /**
     * Add the mapping for $objectClass to $dataSetClass.
     *
     * @param string $objectClass
     * @param string $dataSetClass
     */
    public function addMapping($objectClass, $dataSetClass)
    {
        $this->mappings[$dataSetClass] = $objectClass;
    }

    /**
     * Load the mapped object using the $dataSet.
     *
     * @param DataSet $dataSet
     *
     * @return object
     */
    public function load(DataSet $dataSet)
    {
        $dataSetClass = get_class($dataSet);
        $mappedClass  = $this->mappings[$dataSetClass];
        $object       = new $mappedClass();
        $dataSet->populate($object);

        return $object;
    }
}
