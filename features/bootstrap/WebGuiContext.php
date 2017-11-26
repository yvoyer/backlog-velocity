<?php declare(strict_types=1);

namespace {
    use Behat\Behat\Context\Context;
    use Behat\Behat\Tester\Exception\PendingException;
    use Behat\Gherkin\Node\TableNode;
    use Behat\Transliterator\Transliterator;
    use Doctrine\ORM\Tools\SchemaTool;
    use PHPUnit\Framework\Assert as Assert;
    use Prooph\ServiceBus\CommandBus;
    use Star\Component\Sprint\Application\BacklogBundle\Helpers\ResponseHelper;
    use Star\Component\Sprint\Domain\Handler\CreateProject;
    use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
    use Star\Component\Sprint\Domain\Model\PersonModel;
    use Star\Component\Sprint\Domain\Model\ProjectAggregate;
    use Star\Component\Sprint\Domain\Model\ProjectName;
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
                new CreateProject(
                    ProjectId::fromString(Transliterator::urlize($name)),
                    new ProjectName($name)
                )
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
        }

        /**
         * @Then I should be at url :arg1
         */
        public function iShouldBeAtUrl(string $expectedUrl)
        {
            Assert::assertSame($expectedUrl, $this->response->getCurrentUrl());
        }

        /**
         * @Then I should see the message :arg1
         */
        public function iShouldSeeTheMessage(string $message)
        {
            Assert::assertContains($message, $this->response->filter('body'));
        }
    }
}