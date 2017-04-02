<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Cli\Command\Team;

use Star\Component\Sprint\Collection\PersonCollection;
use Star\Component\Sprint\Collection\TeamCollection;
use Star\Component\Sprint\Command\Team\JoinCommand;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\TeamId;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\PersonName;
use Star\Component\Sprint\Model\TeamModel;
use Star\Component\Sprint\Model\TeamName;
use Star\Component\Sprint\Infrastructure\Cli\Command\CliIntegrationTestCase;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class JoinCommandTestCli extends CliIntegrationTestCase
{
    /**
     * @var JoinCommand
     */
    private $command;

    /**
     * @var TeamCollection
     */
    private $teamRepository;

    /**
     * @var PersonCollection
     */
    private $personRepository;

    /**
     * @var Person
     */
    private $person;

    /**
     * @var Team
     */
    private $team;

    public function setUp()
    {
        $this->person = new PersonModel(PersonId::fromString('id'), new PersonName('name'));
        $this->team = new TeamModel(TeamId::fromString('team-id'), new TeamName('team-name'));

        $this->teamRepository = new TeamCollection();
        $this->personRepository = new PersonCollection();

        $this->command = new JoinCommand($this->teamRepository, $this->personRepository);
    }

    public function test_should_be_a_command()
    {
        $this->assertInstanceOfCommand($this->command, JoinCommand::NAME, 'Link a person to a team.');
    }

    public function test_should_have_a_team_argument()
    {
        $this->assertCommandHasArgument($this->command, JoinCommand::ARGUMENT_TEAM, null, true);
    }

    public function test_should_have_a_sprinter_argument()
    {
        $this->assertCommandHasArgument($this->command, JoinCommand::ARGUMENT_PERSON, null, true);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage Person name must be supplied
     */
    public function test_should_throw_exception_when_person_empty()
    {
        $inputs = array(
            JoinCommand::ARGUMENT_PERSON => '',
            JoinCommand::ARGUMENT_TEAM => '',
        );
        $this->executeCommand($this->command, $inputs);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage Team name must be supplied
     */
    public function test_should_throw_exception_when_team_empty()
    {
        $inputs = array(
            JoinCommand::ARGUMENT_PERSON => 'val',
            JoinCommand::ARGUMENT_TEAM => '',
        );
        $this->executeCommand($this->command, $inputs);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\EntityNotFoundException
     * @expectedExceptionMessage The team could not be found.
     */
    public function test_should_throw_exception_when_team_not_found()
    {
        $inputs = array(
            JoinCommand::ARGUMENT_PERSON => $this->person->getId()->toString(),
            JoinCommand::ARGUMENT_TEAM => 'not-found',
        );
        $this->executeCommand($this->command, $inputs);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\EntityNotFoundException
     * @expectedExceptionMessage The person could not be found.
     */
    public function test_should_throw_exception_when_person_not_found()
    {
        $this->assertTeamIsFound();
        $inputs = array(
            JoinCommand::ARGUMENT_PERSON => 'not-found',
            JoinCommand::ARGUMENT_TEAM => $this->team->getName(),
        );
        $this->executeCommand($this->command, $inputs);
    }

    public function test_should_save_using_the_found_team_and_sprinter()
    {
        $this->assertTeamIsFound();
        $this->assertPersonIsFound();

        $inputs = array(
            JoinCommand::ARGUMENT_PERSON => $this->person->getId()->toString(),
            JoinCommand::ARGUMENT_TEAM => $this->team->getName(),
        );
        $display = $this->executeCommand($this->command, $inputs);

        $this->assertContains("Sprint member 'id' is now part of team 'team-name'.", $display);
    }

    private function assertTeamIsFound()
    {
        $this->teamRepository->saveTeam($this->team);
    }

    private function assertPersonIsFound()
    {
        $this->personRepository->savePerson($this->person);
    }
}
