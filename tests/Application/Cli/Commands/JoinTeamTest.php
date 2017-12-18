<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Infrastructure\Persistence\Collection\PersonCollection;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\TeamCollection;
use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\PersonModel;
use Star\Component\Sprint\Domain\Model\PersonName;
use Star\Component\Sprint\Domain\Model\TeamModel;
use Star\Component\Sprint\Domain\Model\TeamName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class JoinTeamTest extends CliIntegrationTestCase
{
    /**
     * @var JoinTeam
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
        $this->team = TeamModel::create(
            TeamId::fromString('team-id'), new TeamName('team-name')
        );

        $this->teamRepository = new TeamCollection();
        $this->personRepository = new PersonCollection();

        $this->command = new JoinTeam($this->teamRepository, $this->personRepository);
    }

    public function test_should_be_a_command()
    {
        $this->assertInstanceOfCommand($this->command, JoinTeam::NAME, 'Link a person to a team.');
    }

    public function test_should_have_a_team_argument()
    {
        $this->assertCommandHasArgument($this->command, JoinTeam::ARGUMENT_TEAM, null, true);
    }

    public function test_should_have_a_sprinter_argument()
    {
        $this->assertCommandHasArgument($this->command, JoinTeam::ARGUMENT_PERSON, null, true);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Domain\Exception\InvalidArgumentException
     * @expectedExceptionMessage Person name must be supplied
     */
    public function test_should_throw_exception_when_person_empty()
    {
        $inputs = array(
            JoinTeam::ARGUMENT_PERSON => '',
            JoinTeam::ARGUMENT_TEAM => '',
        );
        $this->executeCommand($this->command, $inputs);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Domain\Exception\InvalidArgumentException
     * @expectedExceptionMessage Team name must be supplied
     */
    public function test_should_throw_exception_when_team_empty()
    {
        $inputs = array(
            JoinTeam::ARGUMENT_PERSON => 'val',
            JoinTeam::ARGUMENT_TEAM => '',
        );
        $this->executeCommand($this->command, $inputs);
    }

    public function test_should_throw_exception_when_team_not_found()
    {
        $inputs = array(
            JoinTeam::ARGUMENT_PERSON => $this->person->getId()->toString(),
            JoinTeam::ARGUMENT_TEAM => 'not-found',
        );
        $display = $this->executeCommand($this->command, $inputs);
        $this->assertContains(
            EntityNotFoundException::objectWithAttribute(Team::class, 'name', 'not-found')->getMessage(),
            $display
        );
    }

    public function test_should_throw_exception_when_person_not_found()
    {
        $this->assertTeamIsFound();
        $inputs = array(
            JoinTeam::ARGUMENT_PERSON => 'not-found',
            JoinTeam::ARGUMENT_TEAM => $this->team->getName()->toString(),
        );
        $display = $this->executeCommand($this->command, $inputs);
        $this->assertContains(
            EntityNotFoundException::objectWithAttribute(Person::class, 'name', 'not-found')->getMessage(),
            $display
        );
    }

    public function test_should_save_using_the_found_team_and_sprinter()
    {
        $this->assertTeamIsFound();
        $this->assertPersonIsFound();

        $inputs = array(
            JoinTeam::ARGUMENT_PERSON => $this->person->getName()->toString(),
            JoinTeam::ARGUMENT_TEAM => $this->team->getName()->toString(),
        );
        $display = $this->executeCommand($this->command, $inputs);

        $this->assertContains("Sprint member 'name' is now part of team 'team-name'.", $display);
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
