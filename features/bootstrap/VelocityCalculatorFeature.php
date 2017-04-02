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
    use Star\Component\Sprint\Entity\Sprint;
    use Star\Component\Sprint\Model\Identity\SprintId;
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
        public function theSprintIsCreatedInTheProject($sprintName, $projectId)
        {
            Assert::assertTrue($this->application->createSprint($sprintName, $projectId));
        }

        /**
         * @Given /^The user "([^"]*)" is committed to the sprint "([^"]*)" with (\d+) man days$/
         */
        public function theUserIsCommittedToTheSprintWithManDays($personName, $sprintName, $manDays)
        {
            Assert::assertTrue($this->application->joinSprint($sprintName, $personName, $manDays));
        }

        /**
         * @Given /^The sprint "([^"]*)" is closed with a total of (\d+) man days, an estimate of (\d+) SP, actual of (\d+) SP, focus of (\d+)$/
         */
        public function theSprintIsClosedWithATotalOfManDaysAnEstimateOfSpActualOfSpFocusOf($sprintName, $manDays, $estimated, $actual, $focus)
        {
            $person = $this->persons->allRegistered()[0];
            Assert::assertTrue($this->application->joinSprint($sprintName, $person->getName(), $manDays));
            Assert::assertTrue($this->application->startSprint($sprintName, $estimated));
            Assert::assertTrue($this->application->stopSprint($sprintName, $actual));
        }

        /**
         * @When /^The sprint "([^"]*)" is started with an estimated velocity of (\d+) story points$/
         */
        public function theSprintIsStartedWithAnEstimatedVelocityOfStoryPoints($sprintName, $estimatedPoint)
        {
            Assert::assertTrue($this->application->startSprint($sprintName, $estimatedPoint));
        }

        /**
         * @When /^The user "([^"]*)" is committed to the started sprint "([^"]*)" with (\d+) man days$/
         */
        public function theUserIsCommittedToTheStartedSprintWithManDays($personName, $sprintName, $manDays)
        {
            Assert::assertTrue($this->application->joinSprint($sprintName, $personName, $manDays));
            Assert::assertTrue($this->application->startSprint($sprintName, 0));
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
            return $this->repositoryManager->getSprintRepository()->findOneById(SprintId::fromString($sprintName));
        }
    }
}
