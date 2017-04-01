<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\TeamModel;
use Star\Component\Sprint\UnitTestCase;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 * @covers Star\Component\Sprint\Entity\Factory\BacklogModelTeamFactory
 *
 * @uses Star\Component\Sprint\Collection\SprintCollection
 * @uses Star\Component\Sprint\Collection\SprintMemberCollection
 * @uses Star\Component\Sprint\Collection\TeamMemberCollection
 * @uses Star\Component\Sprint\Model\Identity\TeamId
 * @uses Star\Component\Sprint\Model\PersonModel
 * @uses Star\Component\Sprint\Model\TeamModel
 * @uses Star\Component\Sprint\Type\String
 * @deprecated todo still usefull?
 */
class BacklogModelTeamFactoryTest extends UnitTestCase
{
    /**
     * @var BacklogModelTeamFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new BacklogModelTeamFactory();
    }

    public function test_should_be_a_team_factory()
    {
        $this->assertInstanceOfTeamFactory($this->factory);
    }

    public function test_should_return_a_team()
    {
        $this->assertInstanceOfTeam($this->factory->createTeam('name'));
        $this->assertInstanceOf(TeamModel::class, $this->factory->createTeam('name'));
    }

    public function test_should_return_a_person()
    {
        $this->assertInstanceOfPerson($this->factory->createPerson('name'));
        $this->assertInstanceOf(PersonModel::class, $this->factory->createPerson('name'));
    }
}
