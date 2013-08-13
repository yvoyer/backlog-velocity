<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Repository;

use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class SprintRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Repository
 *
 * @covers Star\Component\Sprint\Entity\Repository\SprintRepository
 */
class SprintRepositoryTest extends UnitTestCase
{
    /**
     * @param \Star\Component\Sprint\Repository\Repository $repository
     * 
     * @return SprintRepository
     */
    private function getRepository(Repository $repository = null)
    {
        $repository = $this->getMockRepository($repository);

        return new SprintRepository($repository);
    }

    public function testShouldBeRepository()
    {
        $this->assertInstanceOf('Star\Component\Sprint\Repository\Repository', $this->getRepository());
    }
}
