<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Star\Component\Sprint\Application\BacklogBundle\AuthenticatedBacklogWebTestCase;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\CreateProjectRequest;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\Request\ProjectInfoRequest;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\TestRequest;

/**
 * @group functional
 * todo transfer to behat
 */
final class ProjectControllerTest extends AuthenticatedBacklogWebTestCase
{
    /**
     * @return TestRequest
     */
    protected function getRequest() :TestRequest
    {
        return new CreateProjectRequest();
    }

    public function test_it_should_show_links_to_project_detail()
    {
        $commitments = [
            [
                'memberId' => 'p1',
                'manDays' => 12,
            ],
            [
                'memberId' => 'p2',
                'manDays' => 34,
            ],
            [
                'memberId' => 'p3',
                'manDays' => 56,
            ],
        ];
        $team = $this->fixture()->team('t1');
        $project = $this->fixture()->emptyProject();
        $pending = $this->fixture()->pendingSprint($project->getIdentity(), $team->getId());
        $started = $this->fixture()->startedSprint($project->getIdentity(), $team->getId(), $commitments);
        $closed = $this->fixture()->closedSprint($project->getIdentity(), $team->getId(), $commitments);

        $response = $this->request(new ProjectInfoRequest($project->getIdentity()));
        $this->assertSame('/project/' . $project->getIdentity()->toString(), $response->getCurrentUrl());

        $crawler = $response->getCrawler()->filter('#project-' . $project->getIdentity()->toString());

        $this->assertNodeContains(
            $project->name()->toString(),
            $crawler,
            'Project name should be visible'
        );
        $this->assertNodeContains(
            $pending->getName()->toString(),
            $crawler,
            'Should show pending sprint of project'
        );
        $this->assertNodeContains(
            $started->getName()->toString(),
            $crawler,
            'Should show started sprint of project'
        );
        $this->assertNodeContains(
            $closed->getName()->toString(),
            $crawler,
            'Should show ended sprint of project'
        );
    }
}
