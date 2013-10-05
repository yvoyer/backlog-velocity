<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Collection;

use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class SprintCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Collection
 */
class SprintCollectionTest extends UnitTestCase
{
    public function testShouldAddSprint()
    {
        $collection = new SprintCollection();
        $sprint     = $this->getMockSprint();

        $this->assertCount(0, $collection->all());
        $collection->add($sprint);
        $this->assertCount(1, $collection->all());
    }
}
