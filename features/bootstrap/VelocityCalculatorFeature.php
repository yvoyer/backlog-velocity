<?php declare(strict_types=1);

namespace Star\BacklogVelocity;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;
use Star\BacklogVelocity\Agile\Domain\Model\PersonRepository;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\Sprint;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine\DoctrinePlugin;
use Star\BacklogVelocity\Agile\RepositoryManager;
use Star\BacklogVelocity\Cli\BacklogApplication;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Features context.
 */
class VelocityCalculatorFeature implements Context
{
    /**
     * @var BacklogApplication
     */
    private $application;

    /**
     * @var RepositoryManager
     */
    private $repositoryManager;

    /**
     * @var PersonRepository
     */
    private $persons;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     */
    public function __construct()
    {
        $testConfig = array(
            'database' => array(
                'in_memory' => true,
                'driver' => 'pdo_sqlite',
            )
        );

        $this->application = new BacklogApplication();
        $this->application->registerPlugin($plugin = DoctrinePlugin::bootstrap($testConfig, 'test'));
        $this->application->setAutoExit(false);
        $this->repositoryManager = $plugin->getRepositoryManager();
        $this->persons = $this->repositoryManager->getPersonRepository();
    }

    /**
     * @Given /^The project \'([^\']*)\' is created$/
     */
    public function theProjectIsCreated($projectName)
    {
        Assert::assertTrue($this->application->createProject($projectName));
    }

    /**
     * @Given /^The following persons are registered$/
     */
    public function theFollowingPersonsAreRegistered(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            Assert::assertTrue($this->application->createPerson($row['name']));
        }
    }

    /**
     * @Given The following teams are registered
     */
    public function theFollowingTeamsAreRegistered(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            Assert::assertTrue($this->application->createTeam($row['name']));
        }
    }

    /**
     * @Given /^The following users are part of team "([^"]*)"$/
     */
    public function theFollowingUsersArePartOfTeam($teamName, TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            Assert::assertTrue($this->application->joinTeam($row['name'], $teamName));
        }
    }

    /**
     * @Given The sprint of team :arg1 with name :arg2 is created in the :arg3 project
     */
    public function theSprintOfTeamWithNameIsCreatedInTheProject($teamId, $sprintId, $projectId)
    {
        Assert::assertTrue($this->application->createSprint($sprintId, $projectId, $teamId));
    }

    /**
     * @Given /^The user "([^"]*)" is committed to the sprint "([^"]*)" of project "([^"]*)" with (\d+) man days$/
     */
    public function theUserIsCommittedToTheSprintOfProjectWithManDays($personName, $sprintName, $projectName, $manDays)
    {
        Assert::assertTrue(
            $this->application->joinSprint(
                ProjectId::fromString($projectName)->toString(),
                $sprintName,
                $personName,
                (int) $manDays,
                new ConsoleOutput()
            )
        );
    }

    /**
     * @Given /^The sprint "([^"]*)" of project "([^"]*)" is closed with a total of (\d+) man days, an estimate of (\d+) SP, actual of (\d+) SP, focus of (\d+)$/
     */
    public function theSprintOfProjectIsClosedWithATotalOfManDaysAnEstimateOfSpActualOfSpFocusOf($sprintName, $projectName, $manDays, $estimated, $actual, $focus)
    {
        $person = $this->persons->allRegistered()[0];
        Assert::assertTrue(
            $this->application->joinSprint($projectName, $sprintName, $person->getName()->toString(), (int) $manDays, new ConsoleOutput())
        );
        Assert::assertTrue($this->application->startSprint($projectName, $sprintName, (int) $estimated));
        Assert::assertTrue($this->application->stopSprint($projectName, $sprintName, (int) $actual));
        Assert::assertSame((int) $focus, $this->getSprint($sprintName, $projectName)->getFocusFactor()->toInt());
    }

    /**
     * @When /^The sprint "([^"]*)" of project "([^"]*)" is started with an estimated velocity of (\d+) story points$/
     */
    public function theSprintOfProjectIsStartedWithAnEstimatedVelocityOfStoryPoints($sprintName, $projectName, $estimatedPoint)
    {
        Assert::assertTrue(
            $this->application->startSprint(
                $projectName,
                $sprintName,
                (int) $estimatedPoint
            )
        );
    }

    /**
     * @When /^The user "([^"]*)" is committed to the started sprint "([^"]*)" of project "([^"]*)" with (\d+) man days$/
     */
    public function theUserIsCommittedToTheStartedSprintOfProjectWithManDays($personName, $sprintName, $projectName, $manDays)
    {
        Assert::assertTrue($this->application->joinSprint($projectName, $sprintName, $personName, (int) $manDays));
        Assert::assertTrue($this->application->startSprint($projectName, $sprintName, 0));
    }

    /**
     * @Then /^The sprint "([^"]*)" of project "([^"]*)" should have an estimated velocity of (\d+) story points$/
     */
    public function theSprintOfProjectShouldHaveAnEstimatedVelocityOfStoryPoints($sprintName, $projectName, $expectedVelocity)
    {
        Assert::assertEquals($expectedVelocity, $this->getSprint($sprintName, $projectName)->getPlannedVelocity()->toInt());
    }

    /**
     * @param string $sprintName
     *
     * @return Sprint
     */
    private function getSprint($sprintName, $projectName)
    {
        return $this->repositoryManager
            ->getSprintRepository()
            ->sprintWithName(ProjectId::fromString($projectName), new SprintName($sprintName));
    }
}
