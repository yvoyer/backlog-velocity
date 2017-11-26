<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Star\Component\Sprint\Application\BacklogBundle\AuthenticatedBacklogWebTestCase;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\TestRequest;

/**
 * @group functional
 */
final class SprintControllerTest extends AuthenticatedBacklogWebTestCase
{
    /**
     * @return TestRequest
     */
    protected function getRequest() :TestRequest
    {
        $this->markTestSkipped('TODO use Sprint Info request');
        // todo CreateSprintRequest contains form to create sprint
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function test_dashboard_has_sprint_creation_link()
    {
        $this->markTestSkipped('TODO');
     //   $this->assertContains("/project/{$project->getIdentity()->toString()}/create-sprint", $link->getUri());

        $link = $this->assertLinkIsFound('Create Sprint', $crawler, 'Create sprint link not found');
        $this->assertSame('GET', $link->getMethod());
        $this->markTestSkipped('TOOD Project create sprint route');
        $this->assertContains("/project/{$project->getIdentity()->toString()}/create-sprint", $link->getUri());
    }

    public function test_dashboard_has_link_to_starting_of_sprint()
    {
        $this->markTestSkipped('TODO');
    }

    public function test_dashboard_has_link_to_ending_of_sprint()
    {
        $this->markTestSkipped('TODO');
    }

    public function test_dashboard_has_link_to_show_sprint()
    {
        $this->markTestSkipped('TODO');
    }
}
