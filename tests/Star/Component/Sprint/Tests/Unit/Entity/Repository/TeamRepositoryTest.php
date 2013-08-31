<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Repository;

use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class TeamRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Repository
 *
 * @covers Star\Component\Sprint\Entity\Repository\TeamRepository
 */
class TeamRepositoryTest extends UnitTestCase
{
    /**
     * @param \Star\Component\Sprint\Repository\Repository $repository
     *
     * @return TeamRepository
     */
    private function getRepository(Repository $repository = null)
    {
        $repository = $this->getMockRepository($repository);

        return new TeamRepository($repository);
    }

    public function testShouldBeRepository()
    {
        $this->assertInstanceOfRepository($this->getRepository());
    }

    public function testShouldBeWrappedRepository()
    {
        $this->assertInstanceOfWrappedRepository($this->getRepository());
    }
}
