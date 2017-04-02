<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Cli\Command\Sprint;

use Star\Component\Sprint\Collection\PersonCollection;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Command\Sprint\JoinSprintCommand;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\PersonName;
use Star\Component\Sprint\Stub\Sprint\StubSprint;
use Star\Component\Sprint\Infrastructure\Cli\Command\CliIntegrationTestCase;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class JoinSprintCommandTestCli extends CliIntegrationTestCase
{
    /**
     * @var SprintCollection
     */
    private $sprints;

    /**
     * @var PersonCollection
     */
    private $persons;

    /**
     * @var JoinSprintCommand
     */
    private $command;

    /**
     * @var Sprint
     */
    private $sprint;

    public function setUp()
    {
        $this->sprint = StubSprint::withId(SprintId::fromString('sprint-name'));

        $this->sprints = new SprintCollection();
        $this->persons = new PersonCollection();

        $this->command = new JoinSprintCommand($this->sprints, $this->persons);
    }

    public function test_should_be_a_command()
    {
        $this->assertInstanceOfCommand($this->command, 'backlog:sprint:join', 'Join a team member to a sprint.');
    }

    /**
     * @dataProvider provideArgumentsData
     */
    public function test_should_have_arguments($argument)
    {
        $this->assertCommandHasArgument($this->command, $argument, null, true);
    }

    public function provideArgumentsData()
    {
        return array(
            array(JoinSprintCommand::ARGUMENT_SPRINT),
            array(JoinSprintCommand::ARGUMENT_PERSON),
            array(JoinSprintCommand::ARGUMENT_MAN_DAYS),
        );
    }

    public function test_should_join_the_sprint()
    {
        $this->sprints->saveSprint($this->sprint);
        $this->persons->savePerson(new PersonModel(PersonId::fromString('person-name'), new PersonName('person-name')));

        $content = $this->executeCommand(
            $this->command,
            array(
                JoinSprintCommand::ARGUMENT_SPRINT => 'sprint-name',
                JoinSprintCommand::ARGUMENT_PERSON => 'person-name',
                JoinSprintCommand::ARGUMENT_MAN_DAYS => 123,
            )
        );
        $this->assertContains(
            "The person 'person-name' is now committed to the 'sprint-name' sprint for '123' man days.",
            $content
        );
    }

    public function test_should_generate_an_error_when_sprint_not_found()
    {
        $content = $this->executeCommand(
            $this->command,
            array(
                JoinSprintCommand::ARGUMENT_SPRINT => 'sprint-name',
                JoinSprintCommand::ARGUMENT_PERSON => 'person-name',
                JoinSprintCommand::ARGUMENT_MAN_DAYS => 123,
            )
        );
        $this->assertContains("The sprint 'sprint-name' can't be found.", $content);
    }

    public function test_should_generate_an_error_when_team_member_not_found()
    {
        $this->sprints->saveSprint($this->sprint);

        $content = $this->executeCommand(
            $this->command,
            array(
                JoinSprintCommand::ARGUMENT_SPRINT => 'sprint-name',
                JoinSprintCommand::ARGUMENT_PERSON => 'person-name',
                JoinSprintCommand::ARGUMENT_MAN_DAYS => 123,
            )
        );
        $this->assertContains(
            "The person with name 'person-name' can't be found.",
            $content
        );
    }
}
