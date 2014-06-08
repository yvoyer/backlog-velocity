<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Entity\Factory;

use Star\Component\Sprint\Entity\Factory\BacklogModelTeamFactory;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\TeamModel;
use tests\UnitTestCase;

/**
 * Class BacklogModelTeamFactoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Entity\Factory
 * @covers Star\Component\Sprint\Entity\Factory\BacklogModelTeamFactory
 *
 * @uses Star\Component\Sprint\Collection\SprintCollection
 * @uses Star\Component\Sprint\Collection\SprintMemberCollection
 * @uses Star\Component\Sprint\Collection\TeamMemberCollection
 * @uses Star\Component\Sprint\Entity\Id\TeamId
 * @uses Star\Component\Sprint\Model\PersonModel
 * @uses Star\Component\Sprint\Model\TeamModel
 * @uses Star\Component\Sprint\Type\String
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
        $this->assertInstanceOf(TeamModel::CLASS_NAME, $this->factory->createTeam('name'));
    }

    public function test_should_return_a_person()
    {
        $this->assertInstanceOfPerson($this->factory->createPerson('name'));
        $this->assertInstanceOf(PersonModel::CLASS_NAME, $this->factory->createPerson('name'));
    }
}
