<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle;

use PHPUnit\Framework\Constraint\LogicalNot;
use Star\Component\Sprint\Application\BacklogBundle\Asserts\LogoutLinkIsPresent;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\ResponseHelper;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\TestRequest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AuthenticatedBacklogWebTestCase extends WebTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @param TestRequest $request
     *
     * @return ResponseHelper
     */
    protected function request(TestRequest $request)
    {
        $client = self::createClient();

        return new ResponseHelper($client, $request->request($client));
    }

    public function test_it_should_show_the_connected_nav_bar()
    {
        $this->assertNavBarConnected($this->request($this->getRequest()));
    }

    public function test_it_should_show_the_not_connected_nav_bar()
    {
        $this->markTestIncomplete('Todo implement with authentication');
        $this->assertNavBarAnonymous($this->request($this->getRequest()));
    }

    /**
     * @return TestRequest
     */
    protected abstract function getRequest();

    /**
     * @param ResponseHelper $response
     */
    protected function assertNavBarConnected(ResponseHelper $response)
    {
        $this->assertThat($response->getCrawler(), new LogoutLinkIsPresent());
    }

    /**
     * @param ResponseHelper $response
     */
    protected function assertNavBarAnonymous(ResponseHelper $response)
    {
        $this->assertThat($response->getCrawler(), new LogicalNot(new LogoutLinkIsPresent()));
    }
}
