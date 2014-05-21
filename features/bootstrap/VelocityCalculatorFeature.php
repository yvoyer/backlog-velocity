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
            $team = $this->teams->findOneByName($teamName);
            foreach ($table->getHash() as $row) {
                $team->addMember($this->persons->findOneByName($row['name']));
            }
        }

        /**
         * @Given /^The team "([^"]*)" already closed the following sprints$/
         */
        public function theTeamAlreadyClosedTheFollowingSprints($teamName, TableNode $table)
        {
//            foreach ($table->getHash() as $row) {
//                $sprintName = $row['name'];
//                $this->backlog->createSprint($sprintName, $teamName);
//                $sprint = $this->backlog->getSprint($sprintName);
//                $sprint->close($row['actual']);
//
//                assertEquals($row['man-days'], $sprint->getAvailableManDays(), 'The man days on closed sprint is incorrect');
//                assertEquals($row['estimated'], $sprint->getEstimatedVelocity(), 'The estimated velocity on closed sprint is incorrect');
//                assertEquals($row['actual'], $sprint->getActualVelocity(), 'The actual velocity on closed sprint is incorrect');
//            }
            throw new PendingException();
        }

       /**
         * @When /^The "([^"]*)" team starts the "([^"]*)" sprint$/
         */
        public function theTeamStartsTheSprint($teamName, $sprintName)
        {
            $team = $this->teams->findOneByName($teamName);
            $this->sprints->add($team->startSprint($sprintName, new ResourceCalculator()));
        }

        /**
         * @Then /^The "([^"]*)" sprint should have an estimated velocity of (\d+) story points$/
         */
        public function theSprintShouldHaveAnEstimatedVelocityOfStoryPoints($sprintName, $expectedVelocity)
        {
            $calculator = new ResourceCalculator;

            \PHPUnit_Framework_Assert::assertSame($expectedVelocity, $calculator->calculateEstimatedVelocity($this->sprints->sprintName, new ResourceCalculator()));
        }
    }
}