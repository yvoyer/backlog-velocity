<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Repository;

use Star\Component\Sprint\Entity\Repository\MemberRepository;
use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class MemberRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Repository
 *
 * @covers Star\Component\Sprint\Entity\Repository\MemberRepository
 */
class MemberRepositoryTest extends UnitTestCase
{
    /**
     * @param \Star\Component\Sprint\Repository\Repository $repository
     *
     * @return MemberRepository
     */
    private function getRepository(Repository $repository = null)
    {
        $repository = $this->getMockRepository($repository);

        return new MemberRepository($repository);
    }

    public function testShouldBeRepository()
    {
        $this->assertInstanceOf('Star\Component\Sprint\Repository\Repository', $this->getRepository());
    }
}
