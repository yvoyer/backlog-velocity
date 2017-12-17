<?php declare(strict_types=1);

namespace {
    use Behat\Behat\Context\Context;
    use Behat\Behat\Tester\Exception\PendingException;
    use Behat\Gherkin\Node\PyStringNode;
    use Behat\Gherkin\Node\TableNode;
    use Behat\Transliterator\Transliterator;
    use Doctrine\ORM\Tools\SchemaTool;
    use PHPUnit\Framework\Assert as Assert;
    use Prooph\ServiceBus\CommandBus;
    use Prooph\ServiceBus\QueryBus;
    use Rhumsaa\Uuid\Uuid;
    use Star\Component\Sprint\Application\BacklogBundle\Helpers\GoToUrl;
    use Star\Component\Sprint\Application\BacklogBundle\Helpers\ResponseHelper;
    use Star\Component\Sprint\Domain\Handler;
    use Star\Component\Sprint\Domain\Model\PersonModel;
    use Star\Component\Sprint\Domain\Model\ProjectAggregate;
    use Star\Component\Sprint\Domain\Model\SprintCommitment;
    use Star\Component\Sprint\Domain\Model\SprintModel;
    use Star\Component\Sprint\Domain\Model\TeamMemberModel;
    use Star\Component\Sprint\Domain\Model\TeamModel;

    final class WebGuiContext implements Context
    {
        /**
         * @var ResponseHelper
         */
        private $response;

        /**
         * @var CommandBus
         */
        private $commandBus;

        /**
         * @var QueryBus
         */
        private $queryBus;

        /**
         * @BeforeScenario
         */
        public function setUp()
        {
            $kernel = new AppKernel('test', true);
            $kernel->boot();
            $container = $kernel->getContainer();
            $client = $container->get('test.client');
            $em = $client->getContainer()->get('doctrine.orm.entity_manager');
            $tool = new SchemaTool($em);
            $tool->dropDatabase();
            $tool->createSchema(
                [
                    $em->getClassMetadata(ProjectAggregate::class),
                    $em->getClassMetadata(TeamModel::class),
                    $em->getClassMetadata(PersonModel::class),
                    $em->getClassMetadata(SprintModel::class),
                    $em->getClassMetadata(SprintCommitment::class),
                    $em->getClassMetadata(TeamMemberModel::class),
                ]
            );
            $this->commandBus = $container->get('prooph_service_bus.backlog_command_bus');
            $this->queryBus = $container->get('prooph_service_bus.backlog_query_bus');
            $this->response = new ResponseHelper($client, $client->request('GET', '/'));
        }

        /**
         * @Given I have a project named :arg1
         */
        public function iHaveAProjectNamed(string $name)
        {
            $this->commandBus->dispatch(
                Handler\CreateProject::fromString(Transliterator::urlize($name), $name)
            );
        }

        /**
         * @Given I have a team named :arg1
         */
        public function iHaveATeamNamed(string $teamName)
        {
            $this->commandBus->dispatch(
                Handler\Project\CreateTeam::fromString($teamName, $teamName)
            );
        }

        /**
         * @Given I have a person named :arg1
         */
        public function iHaveAPersonNamed(string $personId)
        {
            $this->commandBus->dispatch(
                Handler\Project\CreatePerson::fromString($personId, $personId)
            );
        }

        /**
         * @Given I am at url :arg1
         */
        public function iAmAtUrl(string $url)
        {
            $this->response = $this->response->request(new GoToUrl($url));
            Assert::assertSame($url, $this->response->getCurrentUrl());
        }

        /**
         * @Given The test is not implemented yet
         */
        public function theTestIsNotImplementedYet()
        {
            throw new PendingException();
        }

        /**
         * @Given The team :arg1 has a pending sprint with id :arg2 for project :arg3
         */
        public function theTeamHasAPendingSprintWithIdForProject(string $teamId, string $sprintId, string $projectId)
        {
            $this->commandBus->dispatch(Handler\CreateSprint::fromString($sprintId, $projectId, $teamId));
        }

        /**
         * @Given The team :arg1 has the member :arg2
         */
        public function theTeamHasTheMember(string $teamName, string $memberName)
        {
            $this->commandBus->dispatch(Handler\Project\JoinTeam::fromString($teamName, $memberName));
        }

        /**
         * @Given The member :arg1 is committed to pending sprint :arg2 for :arg3 man days
         */
        public function theMemberIsCommittedToPendingSprintForManDays(string $personId, string $sprintId, string $manDays)
        {
            $this->commandBus->dispatch(
                Handler\Sprint\CommitMemberToSprint::fromString($sprintId, $personId, (int) $manDays)
            );
        }

        /**
         * @When I click on link :arg1 inside selector :arg2
         */
        public function iClickOnLinkInsideSelector(string $linkText, string $selector)
        {
            $this->response = $this->response->clickLink($selector, $linkText);
        }

        /**
         * @When I click the :arg1 submit button in form :arg2 with data:
         */
        public function iClickTheSubmitButtonInFormWithData(string $submitButtonText, string $formId, TableNode $table)
        {
            $data = $table->getHash();
            if (! empty($data)) {
               $data = array_pop($data);
            }

            $this->response = $this->response->submitFormAt($formId, $submitButtonText, $data);
            if ($this->response->isRedirect()) {
                $this->response = $this->response->followRedirect();
            }
        }

        /**
         * @Then I should be at url :arg1
         */
        public function iShouldBeAtUrl(string $expectedUrl)
        {
            $actual = $this->response->getCurrentUrl();
            $uidPosition = strpos($expectedUrl, '{UUID}');
            if (false !== $uidPosition) {
                $uuid = substr($actual, $uidPosition, 36);

                Assert::assertTrue(
                    Uuid::isValid($uuid),
                    "Assertion url was expecting a valid UUID arg, but '{$uuid}' is not valid in url '{$actual}'."
                );
                $expectedUrl = str_replace('{UUID}', $uuid, $expectedUrl);
            }

            Assert::assertSame($expectedUrl, $actual);
        }

        /**
         * @Then I should see the flash message :arg1
         */
        public function iShouldSeeTheFlashMessage(string $message)
        {
            Assert::assertContains($message, $this->response->filter('#flash-message'));
        }

        /**
         * @Then The selector :arg1 should contains the text:
         */
        public function theSelectorShouldContainsTheText(string $selector, PyStringNode $string)
        {
            Assert::assertContains(
                $string->getRaw(),
                $this->response->filter($selector),
                "The node '{$selector}' do not contains the expected text."
            );
        }
    }
}
