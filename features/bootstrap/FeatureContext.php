<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Star\Component\Sprint\Calculator\EstimatedVelocityCalculator;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\TeamData;

//
// Require 3rd-party libraries here:
//
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * @var Star\Component\Sprint\Mapping\SprinterData
     */
    private $team;

    /**
     * @var Star\Component\Sprint\Mapping\SprintData
     */
    private $sprint;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->team = null;
    }

    /**
     * @Given /^The following users are part of "([^"]*)" team$/
     */
    public function theFollowingUsersArePartOfTeam($teamName, TableNode $table)
    {
        $this->team = new TeamData($teamName);
        foreach ($table->getHash() as $row) {
            // | name | man-days |
            $this->team->addMember(new SprinterData($row['name']), $row['man-days']);
        }
    }

    /**
     * @When /^I create the "([^"]*)" sprint with a length of (\d+) days$/
     */
    public function iCreateASprintWithALengthOfDays($sprintName, $sprintLength)
    {
    }

    /**
     * @Then /^I should have an estimated velocity of (\d+) story points$/
     */
    public function iShouldHaveAnEstimatedVelocityOfStoryPoints($expectedVelocity)
    {
        $calculator = new EstimatedVelocityCalculator();
        assertEquals($expectedVelocity, $calculator->calculateEstimatedVelocity($this->team));
    }
}
