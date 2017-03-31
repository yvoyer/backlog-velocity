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

    use Star\Component\Sprint\BacklogApplication;
    use Star\Component\Sprint\Calculator\ResourceCalculator;
    use Star\Component\Sprint\Collection\SprintCollection;
    use Star\Component\Sprint\Entity\Sprint;
    use Star\Component\Sprint\Entity\Team;
    use Star\Component\Sprint\Model\Identity\SprintId;
    use Star\Component\Sprint\Repository\RepositoryManager;
    use Star\Plugin\Doctrine\DoctrinePlugin;

    //
    // Require 3rd-party libraries here:
    //
//    require_once 'PHPUnit/Autoload.php';
//    require_once 'PHPUnit/Framework/Assert/Functions.php';

    /**
     * Features context.
     */
    class VelocityCalculatorFeature extends BehatContext
    {
        /**
         * @var Star\Component\Sprint\BacklogApplication
         */
        private $application;

        /**
         * @var RepositoryManager
         */
        private $repositoryManager;

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
        }

        /**
         * @Given /^The following persons are registered$/
         */
        public function theFollowingPersonsAreRegistered(TableNode $table)
        {
            foreach ($table->getHash() as $row) {
                $this->application->createPerson($row['name']);
            }
        }

        /**
         * @Given /^The following teams are registered$/
         */
        public function theFollowingTeamsAreRegistered(TableNode $table)
        {
            foreach ($table->getHash() as $row) {
                $this->application->createTeam($row['name']);
            }
        }

        /**
         * @Given /^The following users are part of team "([^"]*)"$/
         */
        public function theFollowingUsersArePartOfTeam($teamName, TableNode $table)
        {
            foreach ($table->getHash() as $row) {
                $this->application->joinTeam($row['name'], $teamName);
            }
        }

        /**
         * @Given /^The team "([^"]*)" creates the sprint "([^"]*)"$/
         */
        public function theTeamCreatesTheSprint($teamName, $sprintName)
        {
            $this->application->createSprint($sprintName, $teamName);
        }

        /**
         * @Given /^The following users are committing to the sprint "([^"]*)"$/
         */
        public function theFollowingUsersAreCommittingToTheSprint($sprintName, TableNode $table)
        {
            foreach ($table->getHash() as $row) {
                $this->application->joinSprint($sprintName, $row['name'], $row['man-days']);
            }
        }

        /**
         * @Given /^The team "([^"]*)" already closed the following sprints$/
         */
        public function theTeamAlreadyClosedTheFollowingSprints($teamName, TableNode $table)
        {
            foreach ($table->getHash() as $row) {
                $this->application->createSprint($row['name'], $teamName);
                $this->application->joinSprint($row['name'], 'TK-421', $row['man-days']);
                $this->application->startSprint($row['name'], $row['estimated']);
                $this->application->stopSprint($row['name'], $row['actual']);
            }
        }

        /**
         * @When /^The team "([^"]*)" starts the sprint "([^"]*)"$/
         */
        public function theTeamStartsTheSprint($teamName, $sprintName)
        {
            $calculator = new ResourceCalculator();
            $this->application->startSprint(
                $sprintName,
                $calculator->calculateEstimatedVelocity(
                    $this->getSprint($sprintName)->getManDays(),
                    new SprintCollection($this->getTeam($teamName)->getClosedSprints())
                )
            );
        }

        /**
         * @Then /^The sprint "([^"]*)" should have an estimated velocity of (\d+) story points$/
         */
        public function theSprintShouldHaveAnEstimatedVelocityOfStoryPoints($sprintName, $expectedVelocity)
        {
            \PHPUnit_Framework_Assert::assertEquals($expectedVelocity, $this->getSprint($sprintName)->getEstimatedVelocity());
        }

        /**
         * @param string $teamName
         *
         * @return Team
         */
        private function getTeam($teamName)
        {
            return $this->repositoryManager->getTeamRepository()->findOneByName($teamName);
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
