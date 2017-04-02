<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Cli\Command\Team;

use Star\Component\Sprint\Collection\TeamCollection;
use Star\Component\Sprint\Command\Team\AddCommand;
use Star\Component\Sprint\Entity\Factory\BacklogModelTeamFactory;
use Star\Component\Sprint\Model\Identity\TeamId;
use Star\Component\Sprint\Model\TeamModel;
use Star\Component\Sprint\Model\TeamName;
use Star\Component\Sprint\IntegrationTestCase;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class AddCommandTest extends IntegrationTestCase
{
    /**
     * @var TeamCollection
     */
    private $teamRepository;

    /**
     * @var BacklogModelTeamFactory
     */
    private $factory;

    /**
     * @var AddCommand
     */
    private $command;

    public function setUp()
    {
        $this->teamRepository = new TeamCollection();
        $this->factory = new BacklogModelTeamFactory();

        $this->command = new AddCommand($this->teamRepository, $this->factory);
    }

    public function test_should_be_a_command()
    {
        $this->assertInstanceOfCommand($this->command, 'backlog:team:add', 'Add a team.');
    }

    public function test_should_have_a_name_argument()
    {
        $this->assertCommandHasArgument($this->command, 'name', null, true);
    }

    public function test_should_use_the_argument_supplied_as_team_name()
    {
        $this->assertCount(0, $this->teamRepository->allTeams());
        $content = $this->executeCommand($this->command, array('name' => 'teamName'));
        $this->assertContains("The team 'teamName' was successfully saved.", $content);
        $this->assertCount(1, $this->teamRepository->allTeams());
    }

    public function test_should_not_add_team_when_the_team_name_already_exists()
    {
        $this->teamRepository->saveTeam(new TeamModel(TeamId::fromString('teamName'), new TeamName('teamName')));
        $this->assertCount(1, $this->teamRepository->allTeams());

        $content = $this->executeCommand($this->command, array('name' => 'teamName'));

        $this->assertContains("The team 'teamName' already exists.", $content);
        $this->assertCount(1, $this->teamRepository->allTeams());
    }
}
