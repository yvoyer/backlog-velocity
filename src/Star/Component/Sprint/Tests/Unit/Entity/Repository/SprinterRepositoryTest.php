<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Repository;

use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class SprinterRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Repository
 *
 * @covers Star\Component\Sprint\Entity\Repository\SprinterRepository
 */
class SprinterRepositoryTest extends UnitTestCase
{
    /**
     * @param \Star\Component\Sprint\Repository\Repository $repository
     *
     * @return SprinterRepository
     */
    private function getRepository(Repository $repository = null)
    {
        $repository = $this->getMockRepository($repository);

        return new SprinterRepository($repository);
    }

    public function testShouldBeRepository()
    {
        $this->assertInstanceOfRepository($this->getRepository());
    }

    public function testShouldBeWrappedRepository()
    {
        $this->assertInstanceOfWrappedRepository($this->getRepository());
    }

    public function testShouldFindUsingName()
    {
        $name     = 'name-kldahfa823lnfokmwlnq';
        $sprinter = 'entity-laksfVJFAQ012NQD';

        $wrappedRepository = $this->getMockRepository();
        $wrappedRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(array('name' => $name))
            ->will($this->returnValue($sprinter));

        $this->assertSame($sprinter, $this->getRepository($wrappedRepository)->findOneByName($name));
    }
}
