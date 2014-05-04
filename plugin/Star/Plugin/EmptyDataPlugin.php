<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin;

use Star\Component\Sprint\BacklogApplication;
use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\ObjectManager;
use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Mapping\Entity;
use Star\Component\Sprint\Plugin\BacklogPlugin;
use Star\Component\Sprint\Repository\RepositoryManager;

/**
 * Class EmptyDataPlugin
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin
 */
class EmptyDataPlugin implements BacklogPlugin, EntityCreator, EntityFinder, SprinterRepository, SprintRepository,
    TeamRepository, RepositoryManager
{
    /**
     * Returns the entity creator.
     *
     * @return EntityCreator
     */
    public function getEntityCreator()
    {
        return $this;
    }

    /**
     * Returns the entity finder.
     *
     * @return EntityFinder
     */
    public function getEntityFinder()
    {
        return $this;
    }

    /**
     * Returns the repository manager.
     *
     * @return RepositoryManager
     */
    public function getRepositoryManager()
    {
        return $this;
    }

    /**
     * Returns the object manager.
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this;
    }

    /**
     * Hook to inject custom application changes.
     *
     * @param BacklogApplication $application
     */
    public function build(BacklogApplication $application)
    {
    }

    /**
     * Create a sprint object.
     *
     * @param string $name
     * @param string $teamName
     *
     * @return Sprint
     */
    public function createSprint($name, $teamName)
    {
        throw new \RuntimeException('Method createSprint() not implemented yet.');
    }

    /**
     * Create a team object.
     *
     * @param string $name The name of the team.
     *
     * @return Team
     */
    public function createTeam($name)
    {
        throw new \RuntimeException('Method createTeam() not implemented yet.');
    }

    /**
     * Create a SprintMember.
     *
     * @param integer $availableManDays @todo Remove arg, use $teamMember value
     * @param integer $actualVelocity
     * @param Sprint $sprint
     * @param TeamMember $teamMember
     *
     * @return SprintMember
     */
    public function createSprintMember($availableManDays, $actualVelocity, Sprint $sprint, TeamMember $teamMember)
    {
        throw new \RuntimeException('Method createSprintMember() not implemented yet.');
    }

    /**
     * Create a Sprinter.
     *
     * @param string $name The name of the sprinter.
     *
     * @return Sprinter
     */
    public function createSprinter($name)
    {
        throw new \RuntimeException('Method createSprinter() not implemented yet.');
    }

    /**
     * Create a TeamMember.
     *
     * @param Sprinter $sprinter
     * @param Team $team
     * @param integer $availableManDays
     *
     * @return TeamMember
     */
    public function createTeamMember(Sprinter $sprinter, Team $team, $availableManDays)
    {
        throw new \RuntimeException('Method createTeamMember() not implemented yet.');
    }

    /**
     * Find a sprint with $name.
     *
     * @param string $name
     *
     * @return Sprint
     */
    public function findSprint($name)
    {
        throw new \RuntimeException('Method findSprint() not implemented yet.');
    }

    /**
     * Find a sprinter with $name.
     *
     * @param string $name
     *
     * @return Sprinter
     */
    public function findSprinter($name)
    {
        throw new \RuntimeException('Method findSprinter() not implemented yet.');
    }

    /**
     * Find a team with $name.
     *
     * @param string $name
     *
     * @return Team
     */
    public function findTeam($name)
    {
        throw new \RuntimeException('Method findTeam() not implemented yet.');
    }

    /**
     * Returns all the object from one repository.
     *
     * @return array
     */
    public function findAll()
    {
        throw new \RuntimeException('Method findAll() not implemented yet.');
    }

    /**
     * Returns the object linked with the $id.
     *
     * @param mixed $id
     *
     * @return object
     */
    public function find($id)
    {
        throw new \RuntimeException('Method find() not implemented yet.');
    }

    /**
     * Returns the object matching the $criteria.
     *
     * @param array $criteria
     *
     * @return object
     */
    public function findOneBy(array $criteria)
    {
        throw new \RuntimeException('Method findOneBy() not implemented yet.');
    }

    /**
     * Add the $object linked to the $id.
     *
     * @param Entity $object
     */
    public function add(Entity $object)
    {
        throw new \RuntimeException('Method add() not implemented yet.');
    }

    /**
     * Save the $object in the repository.
     */
    public function save()
    {
        throw new \RuntimeException('Method save() not implemented yet.');
    }

    /**
     * Find the object based on name.
     *
     * @param string $name
     *
     * @return Sprinter|null
     */
    public function findOneByName($name)
    {
        throw new \RuntimeException('Method findOneByName() not implemented yet.');
    }

    public function getSprintRepository()
    {
        return $this;
    }

    public function getTeamRepository()
    {
        return $this;
    }

    public function getSprinterRepository()
    {
        return $this;
    }

    public function getTeamMemberRepository()
    {
        return $this;
    }

    /**
     * Returns the Team repository.
     *
     * @return SprintMemberRepository
     */
    public function getSprintMemberRepository()
    {
        throw new \RuntimeException('Method getSprintMemberRepository() not implemented yet.');
    }
}
 