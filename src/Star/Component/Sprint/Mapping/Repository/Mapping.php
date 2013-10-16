<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Mapping\Repository;

/**
 * Interface Mapping
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Mapping\Repository
 */
interface Mapping
{
    /**
     * Return the team mapping.
     *
     * @return string
     */
    public function getTeamMapping();

    /**
     * Return the sprint mapping.
     *
     * @return string
     */
    public function getSprintMapping();

    /**
     * Return the sprinter mapping.
     *
     * @return string
     */
    public function getSprinterMapping();

    /**
     * Return the team member mapping.
     *
     * @return string
     */
    public function getTeamMemberMapping();

    /**
     * Return the sprint member mapping.
     *
     * @return string
     */
    public function getSprintMemberMapping();
}
