<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Mapping;

use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class AbstractValueProvider
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Mapping
 */
abstract class AbstractValueProvider extends UnitTestCase
{
    /**
     * Provides invalid names for validations.
     *
     * @return array
     */
    public function providerInvalidNames()
    {
        return array(
            array(''),
            array(null),
        );
    }

    /**
     * Provides valid names for validations.
     *
     * @return array
     */
    public function providerValidNames()
    {
        return array(
            array(' '),
            array('a'),
            array('1'),
            array(1),
            array(0),
            array('das'),
        );
    }
}
