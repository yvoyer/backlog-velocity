<?php

namespace
{
    use Behat\Behat\Context\ClosuredContextInterface,
        Behat\Behat\Context\TranslatedContextInterface,
        Behat\Behat\Context\BehatContext,
        Behat\Behat\Exception\PendingException;
    use Behat\Behat\Exception\Exception;
    use Behat\Gherkin\Node\PyStringNode,
        Behat\Gherkin\Node\TableNode;

    use Star\Component\Sprint\BacklogApplication;
    use Star\Component\Sprint\Calculator\ResourceCalculator;
    use Star\Component\Sprint\Collection\PersonCollection;
    use Star\Component\Sprint\Collection\SprintCollection;
    use Star\Component\Sprint\Collection\TeamCollection;
    use Star\Component\Sprint\Model\PersonModel;
    use Star\Component\Sprint\Model\SprintModel;
    use Star\Component\Sprint\Model\TeamModel;
    use Star\Plugin\Doctrine\DoctrinePlugin;
    use Symfony\Component\Console\Tester\ApplicationTester;

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
         * @var PersonCollection|PersonModel[]
         */
        private $persons;

        /**
         * @var TeamCollection|TeamModel[]
         */
        private $teams;

        /**
         * @var SprintModel
         */
        private $sprint;

        /**
         * @var TeamModel
         */
        private $team;

        /**
         * @var Star\Component\Sprint\BacklogApplication
         */
        private $application;

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
            $this->application = new BacklogApplication(__DIR__ . '/../..', 'dev', $testConfig);
            $this->application->registerPlugin(new DoctrinePlugin());
            $this->application->setAutoExit(false);
            $this->executeCommand('o:s:d', array('--force' => true));
            $this->executeCommand('o:s:c');

            $this->persons = new PersonCollection();
            $this->teams = new TeamCollection();
        }

        /**
         * @param string $command
         * @param array  $args
         *
         * @return string
         */
        private function executeCommand($command, array $args = array())
        {
            $tester = new ApplicationTester($this->application);
            $errorCode = $tester->run(array_merge(array('command' => $command), $args));
            \PHPUnit_Framework_Assert::assertSame(0, $errorCode, "The command '{$command}' encountered an error with message {$tester->getDisplay()}.");

            return $tester->getDisplay();
        }

        /**
         * @Given /^The following persons are registered$/
         */
        public function theFollowingPersonsAreRegistered(TableNode $table)
        {
            foreach ($table->getHash() as $row) {
                $this->executeCommand('backlog:person:add', array('name' => $row['name']));
                $this->persons->addPerson(new PersonModel($row['name']));
            }
        }

        /**
         * @Given /^The following teams are registered$/
         */
        public function theFollowingTeamsAreRegistered(TableNode $table)
        {
            foreach ($table->getHash() as $row) {
                $this->executeCommand('backlog:team:add', array('name' => $row['name']));
                $this->teams->addTeam(new TeamModel($row['name']));
            }
        }

        /**
         * @Given /^The following users are part of team "([^"]*)"$/
         */
        public function theFollowingUsersArePartOfTeam($teamName, TableNode $table)
        {
           $this->assertSprintIsSet();
            $this->assertTeamIsSet();
            foreach ($table->getHash() as $row) {
                $this->executeCommand('backlog:team:join', array('person' => $row['name'], 'team' => $teamName));
                $person = $this->persons->findOneByName($row['name']);
                $this->team->addTeamMember($person);
            }
        }

        /**
         * @Given /^The team "([^"]*)" creates the sprint "([^"]*)"$/
         */
        public function theTeamCreatesTheSprint($teamName, $sprintName)
        {
            $this->executeCommand(
                'backlog:sprint:add',
                array(
                    'name' => $sprintName,
                    'team' => $teamName,
                )
            );
            $this->team = $this->teams->findOneByName($teamName);
            $this->assertTeamIsSet();
            $this->sprint = $this->team->createSprint($sprintName);
            $this->assertSprintIsSet();
        }

        /**
         * @Given /^The following users are committing to the sprint "([^"]*)"$/
         */
        public function theFollowingUsersAreCommittingToTheSprint($sprintName, TableNode $table)
        {
            $this->assertSprintIsSet();
            $this->assertTeamIsSet();
            foreach ($table->getHash() as $row) {
                $this->executeCommand(
                    'backlog:sprint:join',
                    array(
                        'sprint' => $sprintName,
                        'person' => $row['name'],
                        'man-days' => $row['man-days'],
                    )
                );
                $person = $this->persons->findOneByName($row['name']);
                $this->sprint->commit($this->team->addTeamMember($person), $row['man-days']);
            }
        }

        /**
         * @When /^The team starts the sprint$/
         */
        public function theTeamStartsTheSprint()
        {
            throw new PendingException;
            $calculator = new ResourceCalculator();
            $this->assertSprintIsSet();
            $this->assertTeamIsSet();
            $this->sprint->start(
                $calculator->calculateEstimatedVelocity(
                    $this->sprint->getManDays(),
                    new SprintCollection($this->team->getClosedSprints())
                )
            );
        }

        /**
         * @Given /^The team "([^"]*)" already closed the following sprints$/
         */
        public function theTeamAlreadyClosedTheFollowingSprints($teamName, TableNode $table)
        {
            \PHPUnit_Framework_Assert::assertSame($teamName, $this->team->getName());
            foreach ($table->getHash() as $row) {
                $this->executeCommand(
                    'backlog:sprint:start',
                    array(
                        'name' => $row['name'],
                        'estimated-velocity' => $row['estimated-velocity'],
                    )
                );
                $this->executeCommand(
                    'backlog:sprint:start',
                    array(
                        'name' => $row['name'],
                        'actual-velocity' => $row['actual-velocity'],
                    )
                );
            }
        }

        /**
         * @Then /^The sprint should have an estimated velocity of (\d+) story points$/
         */
        public function theSprintShouldHaveAnEstimatedVelocityOfStoryPoints($expectedVelocity)
        {
            $this->assertSprintIsSet();

            \PHPUnit_Framework_Assert::assertEquals($expectedVelocity, $this->sprint->getEstimatedVelocity());
        }

        private function assertSprintIsSet()
        {
            if (null === $this->sprint) {
                throw new \RuntimeException("The sprint is not set.");
            }
        }

        private function assertTeamIsSet()
        {
            if (null === $this->team) {
                throw new \RuntimeException('The team is not set');
            }
        }
    }
}