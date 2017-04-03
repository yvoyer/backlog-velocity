<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Collection\PersonCollection;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\PersonName;
use Star\Component\Sprint\Model\ProjectName;
use Star\Component\Sprint\Model\SprintModel;
use Star\Component\Sprint\Model\SprintName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class JoinSprintTest extends CliIntegrationTestCase
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
     * @var JoinSprint
     */
    private $command;

    /**
     * @var Sprint
     */
    private $sprint;

    /**
     * @var ProjectId
     */
    private $project;

    public function setUp()
    {
        $this->sprint = SprintModel::notStartedSprint(
            SprintId::fromString('sprint-name'),
            new SprintName('name'),
            $this->project = ProjectId::fromString('p-id'),
            new \DateTime()
        );

        $this->sprints = new SprintCollection();
        $this->persons = new PersonCollection();

        $this->command = new JoinSprint($this->sprints, $this->persons);
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
            array(JoinSprint::ARGUMENT_SPRINT),
            array(JoinSprint::ARGUMENT_PERSON),
            array(JoinSprint::ARGUMENT_MAN_DAYS),
        );
    }

    public function test_should_join_the_sprint()
    {
        $this->sprints->saveSprint($this->sprint);
        $this->persons->savePerson(new PersonModel(PersonId::fromString('person-name'), new PersonName('person-name')));

        $content = $this->executeCommand(
            $this->command,
            array(
                JoinSprint::ARGUMENT_PROJECT => $this->project->toString(),
                JoinSprint::ARGUMENT_SPRINT => $sprintName = $this->sprint->getName()->toString(),
                JoinSprint::ARGUMENT_PERSON => 'person-name',
                JoinSprint::ARGUMENT_MAN_DAYS => 123,
            )
        );
        $this->assertContains(
            "The person 'person-name' is now committed to the sprint 'name' of project 'p-id' for 123 man days.",
            $content
        );
    }

    public function test_should_generate_an_error_when_sprint_not_found()
    {
        $content = $this->executeCommand(
            $this->command,
            array(
                JoinSprint::ARGUMENT_PROJECT => $this->project->toString(),
                JoinSprint::ARGUMENT_SPRINT => 'sprint-name',
                JoinSprint::ARGUMENT_PERSON => 'person-name',
                JoinSprint::ARGUMENT_MAN_DAYS => 123,
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
                JoinSprint::ARGUMENT_PROJECT => $this->project->toString(),
                JoinSprint::ARGUMENT_SPRINT => $sprintName = $this->sprint->getName()->toString(),
                JoinSprint::ARGUMENT_PERSON => 'person-name',
                JoinSprint::ARGUMENT_MAN_DAYS => 123,
            )
        );
        $this->assertContains(
            "The person with name 'person-name' can't be found.",
            $content
        );
    }

    public function test_it_should_add_team_member_to_sprint_of_project()
    {
        $this->persons->savePerson($person = PersonModel::fromString('person-id', 'person-name'));
        $otherSprint = SprintModel::notStartedSprint(
            $this->sprint->getId(),
            $this->sprint->getName(), // Sprint can have same name or id, all depends on project
            $projectId = ProjectId::fromString('other-project'),
            new \DateTime()
        );
        $this->sprints->saveSprint($this->sprint);
        $this->sprints->saveSprint($otherSprint);

        $this->assertCount(0, $this->sprint->getCommitments());
        $this->assertCount(0, $otherSprint->getCommitments());

        $content = $this->executeCommand(
            $this->command,
            array(
                JoinSprint::ARGUMENT_PROJECT => $projectId->toString(),
                JoinSprint::ARGUMENT_SPRINT => $otherSprint->getName()->toString(),
                JoinSprint::ARGUMENT_PERSON => $person->getName()->toString(),
                JoinSprint::ARGUMENT_MAN_DAYS => 123,
            )
        );

        $this->assertCount(0, $this->sprint->getCommitments());
        $this->assertCount(1, $otherSprint->getCommitments());
        $this->assertContains(
            "The person 'person-name' is now committed to the sprint 'name' of project 'other-project' for 123 man days.",
            $content
        );
    }
}
