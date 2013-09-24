<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Entity\Factory\EntityCreatorInterface;
use Star\Component\Sprint\Entity\Query\EntityFinderInterface;

/**
 * Class ObjectManager
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
class ObjectManager
{
    /**
     * @var Factory\EntityCreatorInterface
     */
    private $creator;

    /**
     * @var Query\EntityFinderInterface
     */
    private $finder;

    /**
     * @param EntityCreatorInterface $creator
     * @param EntityFinderInterface  $finder
     */
    public function __construct(
        EntityCreatorInterface $creator,
        EntityFinderInterface $finder
    ) {
        $this->creator = $creator;
        $this->finder  = $finder;
    }

    /**
     * Returns a sprint.
     *
     * @param string $name
     *
     * @return SprintInterface
     */
    public function getSprint($name)
    {
        $sprint = $this->finder->findSprint($name);
        if (null === $sprint) {
            $sprint = $this->creator->createSprint($name);
        }

        return $sprint;
    }
}
