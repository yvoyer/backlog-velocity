<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Tests\Unit\Repository;

use Star\Plugin\Doctrine\Repository\DoctrineSprintMemberRepository;

/**
 * Class DoctrineSprintMemberRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Tests\Unit\Repository
 *
 * @covers Star\Plugin\Doctrine\Repository\DoctrineSprintMemberRepository
 */
class DoctrineSprintMemberRepositoryTest extends DoctrineRepositoryTest
{
    public function setUp()
    {
        parent::setUp();

        /**
         * @var $this->repository DoctrineSprintMemberRepository
         */
        $this->repository = new DoctrineSprintMemberRepository($this->wrappedRepository, $this->objectManager);
    }
}
