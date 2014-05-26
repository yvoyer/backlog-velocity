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

    use Star\Component\Sprint\Calculator\ResourceCalculator;
    use Star\Component\Sprint\Collection\PersonCollection;
    use Star\Component\Sprint\Collection\TeamCollection;
    use Star\Component\Sprint\Model\PersonModel;
    use Star\Component\Sprint\Model\SprintModel;
    use Star\Component\Sprint\Model\TeamModel;

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
         * Initializes context.
         * Every scenario gets it's own context object.
         *
         * @param array $parameters context parameters (set them up through behat.yml)
         */
        public function __construct(array $parameters)
        {
            $this->persons = new PersonCollection();
            $this->teams = new TeamCollection();
        }

        /**
         * @Given /^The following persons are registered$/
         */
        public function theFollowingPersonsAreRegistered(TableNode $table)
        {
            foreach ($table->getHash() as $row) {
                $this->persons->add(new PersonModel($row['name']));
            }
        }

        /**
         * @Given /^The following teams are registered$/
         */
        public function theFollowingTeamsAreRegistered(TableNode $table)
        {
            foreach ($table->getHash() as $row) {
                $this->teams->add(new TeamModel($row['name']));
            }
        }

        /**
         * @Given /^The following users are part of team$/
         */
        public function theFollowingUsersArePartOfTeam(TableNode $table)
        {
            $this->assertSprintIsSet();
            $this->assertTeamIsSet();
            foreach ($table->getHash() as $row) {
                $person = $this->persons->findOneByName($row['name']);
                $this->team->addMember($person);
            }
        }

        /**
         * @Given /^The following users are committing for the sprint$/
         */
        public function theFollowingUsersAreCommittingForTheSprint(TableNode $table)
        {
            $this->assertSprintIsSet();
            $this->assertTeamIsSet();
            foreach ($table->getHash() as $row) {
                $person = $this->persons->findOneByName($row['name']);
                $this->team->addSprintMember($person, $this->sprint, $row['man-days']);
            }
        }

        /**
         * @Given /^The team "([^"]*)" creates the sprint "([^"]*)"$/
         */
        public function theTeamCreatesTheSprint($teamName, $sprintName)
        {
            $this->team = $this->teams->findOneByName($teamName);
            $this->assertTeamIsSet();
            $this->sprint = $this->team->createSprint($sprintName);
            $this->assertSprintIsSet();
        }

        /**
         * @Given /^The team "([^"]*)" already closed the following sprints$/
         */
        public function theTeamAlreadyClosedTheFollowingSprints($teamName, TableNode $table)
        {
            \PHPUnit_Framework_Assert::assertSame($teamName, $this->team->getName());
            foreach ($table->getHash() as $row) {
                $previousSprint = $this->team->createSprint($row['name']);
                $this->team->startSprint($previousSprint, new ResourceCalculator());
                $this->team->closeSprint($row['name'], $row['actual']);
            }
        }

        /**
         * @When /^The team starts the sprint$/
         */
        public function theTeamStartsTheSprint()
        {
            $this->assertSprintIsSet();
            $this->assertTeamIsSet();
            // todo remove startSprint to use the sprint->start()
            $this->team->startSprint($this->sprint, new ResourceCalculator());
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