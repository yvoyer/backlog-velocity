<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Tests\Unit\Repository;

use Star\Plugin\Doctrine\Repository\DoctrineSprintRepository;

/**
 * Class DoctrineSprintRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Tests\Unit\Repository
 *
 * @covers Star\Plugin\Doctrine\Repository\DoctrineSprintRepository
 */
class DoctrineSprintRepositoryTest extends DoctrineRepositoryTest
{
    public function setUp()
    {
        parent::setUp();

        $this->repository = new DoctrineSprintRepository($this->wrappedRepository, $this->objectManager);
    }
}
