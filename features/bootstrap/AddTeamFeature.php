<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\BacklogApplication;
use Symfony\Component\Console\Tester\ApplicationTester;

//
// Require 3rd-party libraries here:
//
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Class AddTeamContext
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 */
class AddTeamFeature extends BehatContext
{
    /**
     * @var BacklogApplication
     */
    private $application;

    /**
     * @var array
     */
    private $input = array();

    /**
     * @var string
     */
    private $display;

    public function __construct()
    {
        $this->application = new BacklogApplication(array());
//        $this->application->setAutoExit(false);
    }

    /**
     * @Given /^I have (\d+) team in the repository$/
     */
    public function iHaveTeamInTheRepository($teamCount)
    {
        $this->application->registerPlugin(new \Star\Plugin\EmptyDataPlugin());
    }

    /**
     * @Given /^I enter \'([^\']*)\' as the team name$/
     */
    public function iEnterAsTheTeamName($teamName)
    {
        $this->input[] = $teamName;
    }

    /**
     * @When /^I launch the command \'([^\']*)\'$/
     */
    public function iLaunchTheCommand($commandName)
    {
        $tester = new ApplicationTester($this->application);
        $tester->run($this->input);
        $this->display = $tester->getDisplay();
    }

    /**
     * @Then /^I should see the message \'([^\']*)\'$/
     */
    public function iShouldSeeTheMessage($message)
    {
        assertContains($message, $this->display);
    }

    /**
     * @Given /^I should have (\d+) team in the repository$/
     */
    public function iShouldHaveTeamInTheRepository($teamCount)
    {
    }

//    /**
//     * @Given /^I have the following teams in the repository \| name \| \| Team-name \|$/
//     */
//    public function iHaveTheFollowingTeamsInTheRepositoryNameTeamName()
//    {
//        throw new PendingException();
//    }
//
//    /**
//     * @Given /^The following users are part of "([^"]*)" team$/
//     */
//    public function theFollowingUsersArePartOfTeam($arg1, TableNode $table)
//    {
//        throw new PendingException();
//    }
//
//    /**
//     * @When /^I create the "([^"]*)" sprint with a length of (\d+) days$/
//     */
//    public function iCreateTheSprintWithALengthOfDays($arg1, $arg2)
//    {
//        throw new PendingException();
//    }
//
//    /**
//     * @Then /^The team available man days should be (\d+) man days$/
//     */
//    public function theTeamAvailableManDaysShouldBeManDays($arg1)
//    {
//        throw new PendingException();
//    }
//
//    /**
//     * @Given /^I should have an estimated velocity of (\d+) story points$/
//     */
//    public function iShouldHaveAnEstimatedVelocityOfStoryPoints($arg1)
//    {
//        throw new PendingException();
//    }
//
//    /**
//     * @Given /^The team already closed the following sprints$/
//     */
//    public function theTeamAlreadyClosedTheFollowingSprints(TableNode $table)
//    {
//        throw new PendingException();
//    }
}
 