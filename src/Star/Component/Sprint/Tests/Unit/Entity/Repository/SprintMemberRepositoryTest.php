<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Repository;

use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class SprintMemberRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Repository
 *
 * @covers Star\Component\Sprint\Entity\Repository\SprintMemberRepository
 */
class SprintMemberRepositoryTest extends UnitTestCase
{
    /**
     * @param \Star\Component\Sprint\Repository\Repository $repository
     *
     * @return SprintMemberRepository
     */
    private function getRepository(Repository $repository = null)
    {
        $repository = $this->getMockRepository($repository);

        return new SprintMemberRepository($repository);
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
