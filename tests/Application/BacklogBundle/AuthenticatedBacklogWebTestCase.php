<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\LogicalNot;
use Star\Component\Sprint\Application\BacklogBundle\Asserts\LogoutLinkIsPresent;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\BacklogFixture;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\ClickOnLink;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\ResponseHelper;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\TestRequest;
use Star\Component\Sprint\Domain\Model;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\DomCrawler\Link;

abstract class AuthenticatedBacklogWebTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    final public function setUp()
    {
        $this->client = self::createClient(['environment' => 'test']);
        $testDb = $this->client->getContainer()->getParameter('database_path');
        if (! file_exists($testDb)) {
            Assert::assertNotFalse(file_put_contents($testDb, ''));
        }
        Assert::assertFileExists($testDb);

        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $tool = new SchemaTool($this->em);
        $tool->createSchema(
            [
                $this->em->getClassMetadata(Model\ProjectAggregate::class),
                $this->em->getClassMetadata(Model\TeamModel::class),
                $this->em->getClassMetadata(Model\PersonModel::class),
                $this->em->getClassMetadata(Model\SprintModel::class),
                $this->em->getClassMetadata(Model\SprintCommitment::class),
                $this->em->getClassMetadata(Model\TeamMemberModel::class),
            ]
        );
    }

    public function tearDown()
    {
        $tool = new SchemaTool($this->em);
        $tool->dropDatabase();

        parent::tearDown();
    }

    protected function fixture() :BacklogFixture
    {
        return new BacklogFixture($this->em);
    }

    protected function request(TestRequest $request) :ResponseHelper
    {
        return new ResponseHelper($this->client, $request->request($this->client));
    }

    public function test_it_should_show_the_connected_nav_bar()
    {
        $this->assertNavBarConnected($this->request($this->getRequest()));
    }

/* todo later
 *     public function test_it_should_show_the_not_connected_nav_bar()
    {
        $this->markTestIncomplete('Todo implement with authentication');
        $this->assertNavBarAnonymous($this->request($this->getRequest()));
    }
*/
    protected function assertNodeContains(string $expected, Crawler $crawler, string $message = '')
    {
        $this->assertGreaterThan(0, count($crawler), 'Selected node do not contains anything');
        $this->assertContains($expected, $crawler->text(), $message);
    }

    protected function assertLinkIsFound(string $linkText, Crawler $crawler, string $message = '') :Link
    {
        $link = $crawler->selectLink($linkText);
        $this->assertGreaterThan(0, count($link), 'Node do not contains a link with text ' . $linkText);

        return $link->link();
    }

    protected function assertLinkGoesToUrl(Link $link, string $url)
    {
        $response = $this->request(new ClickOnLink($link));
        $this->assertSame($url, $response->getCurrentUrl());
    }

    protected function assertFormButtonIsFound(string $buttonText, Crawler $crawler, string $message = '') :Form
    {
        $crawler = $crawler->selectButton($buttonText);
        $this->assertGreaterThan(0, count($crawler), 'Node do not contains a button with text ' . $buttonText);

        return $crawler->form();
    }

    /**
     * @param ResponseHelper $response
     *
     * @return string The redirected url
     */
    protected function assertResponseWasRedirected(ResponseHelper $response) :string
    {
        $this->assertTrue($response->isRedirect(), 'Response was not redirected');
        $newResponse = $response->followRedirect();

        return $newResponse->getCurrentUrl();
    }

    protected abstract function getRequest() :TestRequest;

    protected function assertNavBarConnected(ResponseHelper $response)
    {
        $this->assertThat($response->getCrawler(), new LogoutLinkIsPresent());
    }

    protected function assertNavBarAnonymous(ResponseHelper $response)
    {
        $this->assertThat($response->getCrawler(), new LogicalNot(new LogoutLinkIsPresent()));
    }

    protected function dump(Crawler $crawler)
    {
        echo($crawler->text());
    }
}
