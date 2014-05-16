<?php
///**
// * This file is part of the backlog-velocity.
// *
// * (c) Yannick Voyer (http://github.com/yvoyer)
// */
//
//namespace Star\Component\Sprint\Mapping;
//
//use Star\Component\Sprint\Entity\Person;
//use Star\Component\Sprint\Entity\Sprinter;
//use Star\Component\Sprint\Entity\Team;
//use Star\Component\Sprint\Entity\TeamMember;
//
///**
// * Class TeamMemberData
// *
// * @author  Yannick Voyer (http://github.com/yvoyer)
// *
// * @package Star\Component\Sprint\Mapping
// */
//class TeamMemberData implements TeamMember
//{
//    const LONG_NAME = __CLASS__;
//
//    /**
//     * @var integer
//     */
//    private $id;
//
//    /**
//     * @var Person
//     */
//    private $person;
//
//    /**
//     * @var Team
//     */
//    private $team;
//
//    /**
//     * @var integer
//     */
//    private $availableManDays;
//
//    /**
//     * @param Person $person
//     * @param Team   $team
//     */
//    public function __construct(Person $person, Team $team)
//    {
//        $this->person = $person;
//        $this->team   = $team;
//    }
//
//    /**
//     * Returns the id.
//     *
//     * @return int
//     */
//    public function getId()
//    {
//        return $this->id;
//    }
//
//    /**
//     * Returns the member.
//     *
//     * @return Sprinter
//     */
//    public function getPerson()
//    {
//        return $this->person;
//    }
//
//    /**
//     * Returns the team.
//     *
//     * @return Team
//     */
//    public function getTeam()
//    {
//        return $this->team;
//    }
//
//    /**
//     * Returns the array representation of the object.
//     *
//     * @return array
//     * @deprecated
//     */
//    public function toArray()
//    {
//        return array();// TODO: Implement toArray() method.
//    }
//
//    /**
//     * Returns whether the entity is valid.
//     *
//     * @return bool
//     */
//    public function isValid()
//    {
//        throw new \RuntimeException('Method isValid() not implemented yet.');
//    }
//
//    /**
//     * Set the $manDays.
//     *
//     * @param integer $manDays
//     */
//    public function setAvailableManDays($manDays)
//    {
//        $this->availableManDays = $manDays;
//    }
//
//    /**
//     * Returns the available man days for the team member.
//     *
//     * @return integer
//     */
//    public function getAvailableManDays()
//    {
//        return $this->availableManDays;
//    }
//}
