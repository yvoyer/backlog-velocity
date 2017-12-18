<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Entity\Factory;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Model\PersonModel;
use Star\Component\Sprint\Domain\Model\TeamModel;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 * @deprecated todo still usefull?
 */
class BacklogModelTeamFactoryTest extends TestCase
{
    /**
     * @var BacklogModelTeamFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new BacklogModelTeamFactory();
    }

    public function test_should_return_a_team()
    {
        $this->assertInstanceOf(TeamModel::class, $this->factory->createTeam('name'));
    }

    public function test_should_return_a_person()
    {
        $this->assertInstanceOf(PersonModel::class, $this->factory->createPerson('name'));
    }
}
