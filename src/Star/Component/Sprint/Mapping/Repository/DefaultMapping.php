<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Mapping\Repository;

use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\SprintMemberData;
use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Mapping\TeamMemberData;

/**
 * Class DefaultMapping
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Mapping\Repository
 */
class DefaultMapping implements Mapping
{
    /**
     * Return the team mapping.
     *
     * @return string
     */
    public function getTeamMapping()
    {
        return TeamData::LONG_NAME;
    }

    /**
     * Return the sprint mapping.
     *
     * @return string
     */
    public function getSprintMapping()
    {
        return SprintData::LONG_NAME;
    }

    /**
     * Return the sprinter mapping.
     *
     * @return string
     */
    public function getSprinterMapping()
    {
        return SprinterData::LONG_NAME;
    }

    /**
     * Return the team member mapping.
     *
     * @return string
     */
    public function getTeamMemberMapping()
    {
        return TeamMemberData::LONG_NAME;
    }

    /**
     * Return the sprint member mapping.
     *
     * @return string
     */
    public function getSprintMemberMapping()
    {
        return SprintMemberData::LONG_NAME;
    }
}
