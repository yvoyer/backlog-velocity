<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Star\Component\Sprint\Calculator\ResourceCalculator;
use Star\Component\Sprint\Model\Backlog;

//
// Require 3rd-party libraries here:
//
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
class VelocityCalculatorFeature extends BehatContext
{
    /**
     * @var Star\Component\Sprint\Model\Backlog
     */
    private $backlog;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->backlog = new Backlog();
    }

   /**
     * @Given /^The following persons are registered$/
     */
    public function theFollowingPersonsAreRegistered(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $this->backlog->createPerson($row['name']);
        }
    }

  /**
     * @Given /^The following teams are registered$/
     */
    public function theFollowingTeamsAreRegistered(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $this->backlog->createTeam($row['name']);
        }
    }

    /**
     * @Given /^The following users are part of "([^"]*)" team$/
     */
    public function theFollowingUsersArePartOfTeam($teamName, TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $this->backlog->addTeamMember($teamName, $row['name']);
        }
    }

    /**
     * @Given /^The team "([^"]*)" already closed the following sprints$/
     */
    public function theTeamAlreadyClosedTheFollowingSprints2($teamName, TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $sprintName = $row['name'];
            $this->backlog->createSprint($sprintName, $teamName);
            $this->backlog->startSprint($sprintName);
            $this->backlog->closeSprint($sprintName, $row['actual']);
            $sprint = $this->backlog->getSprint($sprintName);

            assertEquals($row['man-days'], $sprint->getAvailableManDays(), 'The man days on closed sprint is incorrect');
            assertEquals($row['estimated'], $sprint->getEstimatedVelocity(), 'The estimated velocity on closed sprint is incorrect');
            assertEquals($row['actual'], $sprint->getActualVelocity(), 'The actual velocity on closed sprint is incorrect');
        }
    }

   /**
     * @When /^The "([^"]*)" team create the "([^"]*)" sprint$/
     */
    public function theTeamCreateTheSprint($teamName, $sprintName)
    {
        $this->backlog->createSprint($sprintName, $teamName);
    }

    /**
     * @Given /^Start the sprint "([^"]*)" with a length of (\d+) days$/
     */
    public function startTheSprintWithALengthOfDays($sprintName, $sprintLength)
    {
        $this->backlog->startSprint($sprintName);
    }

    /**
     * @Then /^The "([^"]*)" sprint should have an estimated velocity of (\d+) story points$/
     */
    public function theSprintShouldHaveAnEstimatedVelocityOfStoryPoints($sprintName, $expectedVelocity)
    {
        assertEquals($expectedVelocity, $this->backlog->estimateVelocity($sprintName, new ResourceCalculator()));
    }
}
