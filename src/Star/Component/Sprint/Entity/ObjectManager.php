<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\Query\EntityFinder;

/**
 * Class ObjectManager
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
class ObjectManager
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var Factory\EntityCreator
     */
    private $creator;

    /**
     * @var Query\EntityFinder
     */
    private $finder;

    /**
     * @param EntityCreator $creator
     * @param EntityFinder  $finder
     */
    public function __construct(
        EntityCreator $creator,
        EntityFinder $finder
    ) {
        $this->creator = $creator;
        $this->finder  = $finder;
    }

    /**
     * Returns a sprint.
     *
     * @param string $name
     *
     * @return Sprint
     */
    public function getSprint($name)
    {
        $sprint = $this->finder->findSprint($name);
        if (null === $sprint) {
            $sprint = $this->creator->createSprint($name);
        }

        return $sprint;
    }

    /**
     * Returns a team.
     *
     * @param string $teamName
     *
     * @return Team
     */
    public function getTeam($teamName)
    {
        $team = $this->finder->findTeam($teamName);
        if (null === $team) {
            $team = $this->creator->createTeam($teamName);
        }

        return $team;
    }

    /**
     * Returns a sprinter.
     *
     * @param string $sprinterName
     *
     * @return Sprinter
     */
    public function getSprinter($sprinterName)
    {
        $sprinter = $this->finder->findSprinter($sprinterName);
        if (null === $sprinter) {
            $sprinter = $this->creator->createSprinter($sprinterName);
        }

        return $sprinter;
    }
}
