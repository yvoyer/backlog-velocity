<?php

namespace
{
    use Behat\Behat\Context\ClosuredContextInterface;
    use Behat\Behat\Context\TranslatedContextInterface;
    use Behat\Behat\Context\BehatContext;
    use Behat\Behat\Exception\PendingException;
    use Behat\Behat\Exception\Exception;
    use Behat\Gherkin\Node\PyStringNode;
    use Behat\Gherkin\Node\TableNode;

    use Star\BacklogVelocity\Application\Cli\BacklogApplication;
    use Star\Component\Sprint\Entity\Repository\Filters\AllObjects;
    use Star\Component\Sprint\Entity\Sprint;
    use Star\Component\Sprint\Model\Identity\ProjectId;
    use Star\Component\Sprint\Model\Identity\SprintId;
    use Star\Component\Sprint\Model\SprintName;
    use Star\Component\Sprint\Repository\RepositoryManager;
    use Star\Plugin\Doctrine\DoctrinePlugin;

    use PHPUnit_Framework_Assert as Assert;
    use Symfony\Component\Console\Output\ConsoleOutput;

    /**
     * Features context.
     */
    class VelocityCalculatorFeature extends BehatContext
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
         * @var \Star\Component\Sprint\Entity\Repository\PersonRepository
         */
        private $persons;

        /**
         * @var ProjectId[]
         */
        private $projectMap = [];

        /**
         * Initializes context.
         * Every scenario gets it's own context object.
         *
         * @param array $parameters context parameters (set them up through behat.yml)
         */
        public function __construct(array $parameters)
        {
            $testConfig = array(
                'database' => array(
                    'in_memory' => true,
                    'driver' => 'pdo_sqlite',
                )
            );

            $this->application = new BacklogApplication($rootPath = __DIR__ . '/../..', $env = 'dev', $testConfig);
            $plugin = DoctrinePlugin::bootstrap($testConfig, $env, $rootPath);
            $this->application->registerPlugin($plugin);
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
         * @Given /^The following teams are registered$/
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
         * @Given /^The sprint "([^"]*)" is created in the "([^"]*)" project$/
         */
        public function theSprintIsCreatedInTheProject($sprintName, $projectName)
        {
            $this->projectMap[$sprintName] = ProjectId::fromString($projectName);
            Assert::assertTrue($this->application->createSprint($sprintName, $projectName));
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
                    $manDays,
                    new ConsoleOutput()
                )
            );
        }

        /**
         * @Given /^The sprint "([^"]*)" is closed with a total of (\d+) man days, an estimate of (\d+) SP, actual of (\d+) SP, focus of (\d+)$/
         */
        public function theSprintIsClosedWithATotalOfManDaysAnEstimateOfSpActualOfSpFocusOf($sprintName, $manDays, $estimated, $actual, $focus)
        {
            $project = $this->getSprint($sprintName)->getName()->toString();
            $person = $this->persons->allRegistered()[0];
            Assert::assertTrue(
                $this->application->joinSprint($project, $sprintName, $person->getName()->toString(), $manDays, new ConsoleOutput())
            );
            Assert::assertTrue($this->application->startSprint($project, $sprintName, $estimated));
            Assert::assertTrue($this->application->stopSprint($project, $sprintName, $actual));
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
                    $estimatedPoint
                )
            );
        }

        /**
         * @When /^The user "([^"]*)" is committed to the started sprint "([^"]*)" with (\d+) man days$/
         */
        public function theUserIsCommittedToTheStartedSprintWithManDays($personName, $sprintName, $manDays)
        {
            $project = $this->getSprint($sprintName)->getName()->toString();
            Assert::assertTrue($this->application->joinSprint($project, $sprintName, $personName, $manDays));
            Assert::assertTrue($this->application->startSprint($project, $sprintName, 0));
        }

        /**
         * @Then /^The sprint "([^"]*)" should have an estimated velocity of (\d+) story points$/
         */
        public function theSprintShouldHaveAnEstimatedVelocityOfStoryPoints($sprintName, $expectedVelocity)
        {
            Assert::assertEquals($expectedVelocity, $this->getSprint($sprintName)->getEstimatedVelocity());
        }

        /**
         * @param string $sprintName
         *
         * @return Sprint
         */
        private function getSprint($sprintName)
        {
            return $this->repositoryManager
                ->getSprintRepository()
                ->sprintWithName($this->projectMap[$sprintName], new SprintName($sprintName));
        }
    }
}
