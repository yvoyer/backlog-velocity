<?php

namespace
{
    use Behat\Behat\Context\ClosuredContextInterface,
        Behat\Behat\Context\TranslatedContextInterface,
        Behat\Behat\Context\BehatContext,
        Behat\Behat\Exception\PendingException;
    use Behat\Gherkin\Node\PyStringNode,
        Behat\Gherkin\Node\TableNode;

    use Star\Component\Sprint\Calculator\ResourceCalculator;
    use Star\Component\Sprint\Collection\PersonCollection;
    use Star\Component\Sprint\Collection\SprintCollection;
    use Star\Component\Sprint\Collection\TeamCollection;
    use Star\Component\Sprint\Entity\Person;
    use Star\Component\Sprint\Entity\Sprint;
    use Star\Component\Sprint\Entity\Team;
    use Star\Component\Sprint\Model\Backlog;
    use Star\Component\Sprint\Model\PersonModel;
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
         * @var Star\Component\Sprint\Collection\PersonCollection|Person[]
         */
        private $persons;

        /**
         * @var Star\Component\Sprint\Collection\TeamCollection|Team[]
         */
        private $teams;

        /**
         * @var Star\Component\Sprint\Collection\SprintCollection|Sprint[]
         */
        private $sprints;

        /**
         * @var Team
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
            $this->sprints = new SprintCollection();
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
         * @Given /^The following users are part of "([^"]*)" team$/
         */
        public function theFollowingUsersArePartOfTeam($teamName, TableNode $table)
        {
            $this->team = $this->teams->findOneByName($teamName);
            foreach ($table->getHash() as $row) {
                $teamMember = $this->team->addMember($this->persons->findOneByName($row['name']));
                $teamMember->setAvailableManDays($row['man-days']);
            }
        }

        /**
         * @Given /^The team "([^"]*)" already closed the following sprints$/
         */
        public function theTeamAlreadyClosedTheFollowingSprints($teamName, TableNode $table)
        {
            \PHPUnit_Framework_Assert::assertSame($teamName, $this->team->getName());
            foreach ($table->getHash() as $row) {
                $this->team->startSprint($row['name'], new ResourceCalculator());
                $this->team->closeSprint($row['name'], $row['actual']);
            }
        }

        /**
         * @When /^The "([^"]*)" team starts the "([^"]*)" sprint$/
         */
        public function theTeamStartsTheSprint($teamName, $sprintName)
        {
            \PHPUnit_Framework_Assert::assertSame($teamName, $this->team->getName());
            $this->sprints->add($this->team->startSprint($sprintName, new ResourceCalculator()));
        }

        /**
         * @Then /^The "([^"]*)" sprint should have an estimated velocity of (\d+) story points$/
         */
        public function theSprintShouldHaveAnEstimatedVelocityOfStoryPoints($sprintName, $expectedVelocity)
        {
            $sprint = $this->sprints->findOneByName($sprintName);
            if (null === $sprint) {
                throw new \RuntimeException("The sprint {$sprintName} was not found.");
            }

            \PHPUnit_Framework_Assert::assertEquals($expectedVelocity, $sprint->getEstimatedVelocity());
        }
    }
}