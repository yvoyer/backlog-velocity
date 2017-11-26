<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Star\Component\Sprint\Application\BacklogBundle\AuthenticatedBacklogWebTestCase;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\ClickOnLink;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\DashboardRequest;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\Request\ProjectInfoRequest;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\TestRequest;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\ManDays;
use Star\Component\Sprint\Domain\Port\CommitmentDTO;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
final class DashboardControllerTest extends AuthenticatedBacklogWebTestCase
{
    /**
     * @return TestRequest
     */
    protected function getRequest() :TestRequest
    {
        return new DashboardRequest();
    }

    public function test_it_should_show_empty_projects()
    {
        $project = $this->fixture()->emptyProject();
        $response = $this->request($this->getRequest());

        $this->assertSame(Response::HTTP_OK, $response->getStatus());
        $selector = '#project-' . $project->getIdentity()->toString();

        $crawler = $response->getCrawler()->filter($selector);
        $this->assertCount(1, $crawler);
        $this->assertNodeContains(
            $project->name()->toString(),
            $crawler->filter(''),
            'project name should appear'
        );
        $this->assertNodeContains(
            'No active sprint yet...',
            $crawler->filter('.sprint'),
            'Project active sprint info should appear'
        );
    }

    public function test_it_should_show_links_to_project_detail()
    {
        $project = $this->fixture()->emptyProject();

        $response = $this->request($this->getRequest());
        $link = $this->assertLinkIsFound(
            $project->name()->toString(),
            $response->getCrawler()->filter('#project-' . $project->getIdentity()->toString()),
            'Link to show project should be present'
        );
        $this->assertLinkGoesToUrl($link, $url = '/project/' . $project->getIdentity()->toString());

        $response = $this->request(new ClickOnLink($link));
        $this->assertSame($url, $response->getCurrentUrl());
    }

    public function test_it_should_show_link_to_dashboard()
    {
        $response = $this->request($this->getRequest());
        $link = $this->assertLinkIsFound(
            'Home',
            $crawler = $response->getCrawler(),
            'Home link link not found'
        );

        $this->assertLinkGoesToUrl($link, '/');
    }
}
