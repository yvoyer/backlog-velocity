<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Exception\EntityAlreadyExistsException;
use Star\Component\Sprint\Model\TeamModel;

/**
 * Class BacklogService
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
class BacklogService
{
    /**
     * @var Repository\PersonRepository
     */
    private $personRepository;

    /**
     * @var Repository\SprintRepository
     */
    private $sprintRepository;

    /**
     * @var Repository\TeamRepository
     */
    private $teamRepository;

    /**
     * @var Repository\TeamMemberRepository
     */
    private $teamMemberRepository;

    /**
     * @var Repository\SprintMemberRepository
     */
    private $sprintMemberRepository;

    /**
     * @param PersonRepository $personRepository
     * @param SprintRepository $sprintRepository
     * @param TeamRepository $teamRepository
     * @param TeamMemberRepository $teamMemberRepository
     * @param SprintMemberRepository $sprintMemberRepository
     */
    public function __construct(
        PersonRepository $personRepository,
        SprintRepository $sprintRepository,
        TeamRepository $teamRepository,
        TeamMemberRepository $teamMemberRepository,
        SprintMemberRepository $sprintMemberRepository
    ) {
        $this->personRepository = $personRepository;
        $this->sprintRepository = $sprintRepository;
        $this->teamRepository = $teamRepository;
        $this->teamMemberRepository = $teamMemberRepository;
        $this->sprintMemberRepository = $sprintMemberRepository;
    }

    /**
     * @param $name
     *
     * @return null|Team|TeamModel
     * @throws \Star\Component\Sprint\Exception\EntityAlreadyExistsException
     */
    public function createTeam($name)
    {
        $team = $this->teamRepository->findOneByName($name);
        if ($team) {
            throw new EntityAlreadyExistsException('The team already exists.');
        }

        $team = new TeamModel($name);
        $this->teamRepository->add($team);
        $this->teamRepository->save();

        return $team;
    }

    public function createPerson()
    {

    }

    public function createSprint()
    {

    }

    public function joinSprint()
    {

    }

    public function joinTeam()
    {

    }

    public function startSprint()
    {

    }

    public function endSprint()
    {

    }
}
 