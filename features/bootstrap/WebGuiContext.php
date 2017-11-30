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
    use Rhumsaa\Uuid\Uuid;
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
         * @var object|\Symfony\Bundle\FrameworkBundle\Client
         */
        private $client;

        /**
         * @var ResponseHelper
         */
        private $response;

        /**
         * @var CommandBus
         */
        private $commandBus;

        public function __construct()
        {
            $kernel = new AppKernel('test', true);
            $kernel->boot();
            $container = $kernel->getContainer();
            $this->client = $container->get('test.client');

            $this->commandBus = $container->get('prooph_service_bus.backlog_command_bus');
        }

        /**
         * @Given I have a project named :arg1
         */
        public function iHaveAProjectNamed(string $name)
        {
            $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
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

            $this->commandBus->dispatch(
                Handler\CreateProject::fromString(Transliterator::urlize($name), $name)
            );
        }

        /**
         * @Given I am at url :arg1
         */
        public function iAmAtUrl(string $url)
        {
            $crawler = $this->client->request('GET', $url);
            $this->response = new ResponseHelper($this->client, $crawler);
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
         * @Given The project :arg1 has a pending sprint with id :arg2
         */
        public function theProjectHasAPendingSprintWithId(string $projectId, string $sprintId)
        {
            $this->commandBus->dispatch(Handler\CreateSprint::fromString($projectId, $sprintId));
        }

        /**
         * @Given The member :arg1 is committed to pending sprint :arg2 for :arg3 man days
         */
        public function theMemberIsCommittedToPendingSprintForManDays($personId, $sprintId, $manDays)
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
         * @When I submit the form :arg1 with data:
         */
        public function iSubmitTheFormWithData(string $formId, TableNode $table)
        {
            $this->response = $this->response->submitFormAt($formId, $table->getHash());
            $this->response->dump();
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
        public function theSelectorShouldContainsTheText($selector, PyStringNode $string)
        {
            Assert::assertContains(
                $string->getRaw(),
                $this->response->filter($selector),
                "The node '{$selector}' do not contains the expected text."
            );
        }
    }
}
