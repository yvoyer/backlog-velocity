<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Commands;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\InvalidArgumentException;
use Star\BacklogVelocity\Agile\Domain\Model\Person;
use Star\BacklogVelocity\Agile\Domain\Model\PersonId;
use Star\BacklogVelocity\Agile\Domain\Model\PersonModel;
use Star\BacklogVelocity\Agile\Domain\Model\PersonName;
use Star\BacklogVelocity\Agile\Domain\Model\Team;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamModel;
use Star\BacklogVelocity\Agile\Domain\Model\TeamName;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\PersonCollection;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\TeamCollection;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class JoinTeamTest extends CliIntegrationTestCase
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

	protected function setUp(): void
    {
        $this->person = new PersonModel(PersonId::fromString('id'), new PersonName('name'));
        $this->team = TeamModel::create(
            TeamId::fromString('team-id'), new TeamName('team-name')
        );

        $this->teamRepository = new TeamCollection();
        $this->personRepository = new PersonCollection();

        $this->command = new JoinTeam($this->teamRepository, $this->personRepository);
    }

    public function test_should_be_a_command(): void
    {
        $this->assertInstanceOfCommand($this->command, JoinTeam::NAME, 'Link a person to a team.');
    }

    public function test_should_have_a_team_argument(): void
    {
        $this->assertCommandHasArgument($this->command, JoinTeam::ARGUMENT_TEAM, null, true);
    }

    public function test_should_have_a_sprinter_argument(): void
    {
        $this->assertCommandHasArgument($this->command, JoinTeam::ARGUMENT_PERSON, null, true);
    }

    public function test_should_throw_exception_when_person_empty(): void
    {
        $inputs = array(
            JoinTeam::ARGUMENT_PERSON => '',
            JoinTeam::ARGUMENT_TEAM => '',
        );
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Person name must be supplied');
        $this->executeCommand($this->command, $inputs);
    }

    public function test_should_throw_exception_when_team_empty(): void
    {
        $inputs = array(
            JoinTeam::ARGUMENT_PERSON => 'val',
            JoinTeam::ARGUMENT_TEAM => '',
        );
	    $this->expectException(InvalidArgumentException::class);
	    $this->expectExceptionMessage('Team name must be supplied');
        $this->executeCommand($this->command, $inputs);
    }

    public function test_should_throw_exception_when_team_not_found(): void
    {
        $inputs = array(
            JoinTeam::ARGUMENT_PERSON => $this->person->getName()->toString(),
            JoinTeam::ARGUMENT_TEAM => 'not-found',
        );
        $display = $this->executeCommand($this->command, $inputs);
        $this->assertStringContainsString(
            EntityNotFoundException::objectWithAttribute(Team::class, 'name', 'not-found')->getMessage(),
            $display
        );
    }

    public function test_should_throw_exception_when_person_not_found(): void
    {
        $this->assertTeamIsFound();
        $inputs = array(
            JoinTeam::ARGUMENT_PERSON => 'not-found',
            JoinTeam::ARGUMENT_TEAM => $this->team->getName()->toString(),
        );
        $display = $this->executeCommand($this->command, $inputs);
        $this->assertStringContainsString(
            EntityNotFoundException::objectWithAttribute(Person::class, 'name', 'not-found')->getMessage(),
            $display
        );
    }

    public function test_should_save_using_the_found_team_and_sprinter(): void
    {
        $this->assertTeamIsFound();
        $this->assertPersonIsFound();

        $inputs = array(
            JoinTeam::ARGUMENT_PERSON => $this->person->getName()->toString(),
            JoinTeam::ARGUMENT_TEAM => $this->team->getName()->toString(),
        );
        $display = $this->executeCommand($this->command, $inputs);

        $this->assertStringContainsString("Sprint member 'name' is now part of team 'team-name'.", $display);
    }

    private function assertTeamIsFound(): void
    {
        $this->teamRepository->saveTeam($this->team);
    }

    private function assertPersonIsFound(): void
    {
        $this->personRepository->savePerson($this->person);
    }
}
