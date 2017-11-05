<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Star\Component\Sprint\Application\BacklogBundle\BacklogWebTestCase;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\DashboardRequest;
use Symfony\Component\HttpFoundation\Response;

final class DashboardControllerTest extends BacklogWebTestCase
{
    public function test_it_should_show_the_dashboard()
    {
        $response = $this->request(new DashboardRequest());

        $this->assertSame(Response::HTTP_OK, $response->getStatus());
        $this->assertContains('Backlog', $response->filter('title'));
        $this->assertContains('Welcome to Symfony', $response->filter('body'));
    }
}
