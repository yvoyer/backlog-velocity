<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository;

/**
 * Class Mapping
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository
 */
class Mapping
{
    /**
     * @var array of mappings
     */
    private $map = array(
        'team' => '',
        'sprint' => '',
        'sprinter' => '',
        'team-member' => '',
        'sprint-member' => '',
    );

    /**
     * @param string $team
     * @param string $sprint
     * @param string $sprinter
     * @param string $teamMember
     * @param string $sprintMember
     */
    public function __construct($team, $sprint, $sprinter, $teamMember, $sprintMember)
    {
        $this->map['team'] = $team;
        $this->map['sprint'] = $sprint;
        $this->map['sprinter'] = $sprinter;
        $this->map['team-member'] = $teamMember;
        $this->map['sprint-member'] = $sprintMember;
    }

    /**
     * Return the team mapping.
     *
     * @return string
     */
    public function getTeamMapping()
    {
        return $this->map['team'];
    }

    /**
     * Return the sprint mapping.
     *
     * @return string
     */
    public function getSprintMapping()
    {
        return $this->map['sprint'];
    }

    /**
     * Return the sprinter mapping.
     *
     * @return string
     */
    public function getSprinterMapping()
    {
        return $this->map['sprinter'];
    }

    /**
     * Return the team member mapping.
     *
     * @return string
     */
    public function getTeamMemberMapping()
    {
        return $this->map['team-member'];
    }

    /**
     * Return the sprint member mapping.
     *
     * @return string
     */
    public function getSprintMemberMapping()
    {
        return $this->map['sprint-member'];
    }
}
