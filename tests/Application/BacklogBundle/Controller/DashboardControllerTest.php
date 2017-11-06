<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Star\Component\Sprint\Application\BacklogBundle\AuthenticatedBacklogWebTestCase;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\DashboardRequest;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\TestRequest;
use Symfony\Component\HttpFoundation\Response;

final class DashboardControllerTest extends AuthenticatedBacklogWebTestCase
{
    /**
     * @return TestRequest
     */
    protected function getRequest()
    {
        return new DashboardRequest();
    }

    public function test_it_should_show_the_dashboard()
    {
        $response = $this->request($this->getRequest());

        $this->assertSame(Response::HTTP_OK, $response->getStatus());
        $this->markTestIncomplete('TODO assert content of dashboard and side bars');
        $this->assertContains('Backlog', $response->filter('title'));
        $this->assertContains('Welcome to Symfony', $response->filter('body'));
    }
}
