<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Star\Component\Sprint\Application\BacklogBundle\AuthenticatedBacklogWebTestCase;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\CreateProjectRequest;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\Request\ProjectInfoRequest;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\ResponseHelper;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\TestRequest;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\ManDays;
use Star\Component\Sprint\Domain\Port\CommitmentDTO;

/**
 * @group functional
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

    public function test_it_should_forward_to_project_creation_form()
    {
        $response = $this->request($this->getRequest());
        $link = $this->assertLinkIsFound(
            'New project',
            $crawler = $response->getCrawler(),
            'Project creation link not found'
        );

        $this->assertLinkGoesToUrl($link, '/project');
    }

    public function test_it_should_show_the_create_project_form()
    {
        $response = $this->request($this->getRequest());
        $form = $this->assertFormButtonIsFound(
            'Create project',
            $response->getCrawler(),
            'The create project button was not found'
        );
        $this->assertTrue($form->has('project[name]'), 'The form field name do not exists');
    }

    public function test_it_should_save_the_project_on_valid_form()
    {
        $response = $this->assertProjectFormIsSubmitted('name');
        $this->assertContains('/project/', $this->assertResponseWasRedirected($response));
    }

    public function test_it_should_show_errors_when_name_empty_on_submit()
    {
        $response = $this->assertProjectFormIsSubmitted('');
        $this->assertFalse($response->isRedirect());
        $this->assertSame('/project', $response->getCurrentUrl());
        $this->assertContains(
            'The project name should not be blank.',
            $response->filter('#project')
        );
    }

    public function test_it_should_fail_when_project_already_exists()
    {
        $this->assertProjectFormIsSubmitted('name');
        $response = $this->assertProjectFormIsSubmitted('name');
        $this->assertContains(
            'The project with name name already exists.',
            $response->filter('#project')
        );
    }

    public function test_it_should_show_links_to_project_detail()
    {
        $commitments = [
            new CommitmentDTO(PersonId::uuid(), ManDays::random()),
            new CommitmentDTO(PersonId::uuid(), ManDays::random()),
            new CommitmentDTO(PersonId::uuid(), ManDays::random()),
        ];
        $project = $this->fixture()->emptyProject();
        $pending = $this->fixture()->pendingSprint($project->getIdentity());
        $started = $this->fixture()->startedSprint($project->getIdentity(), $commitments);
        $closed = $this->fixture()->closedSprint($project->getIdentity(), $commitments);

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

    /**
     * @param string $name
     *
     * @return ResponseHelper
     */
    private function assertProjectFormIsSubmitted(string $name): ResponseHelper
    {
        $response = $this->request($this->getRequest());
        $form = $this->assertFormButtonIsFound(
            'Create project',
            $response->getCrawler(),
            'The create project button was not found'
        );
        $response = $response->submitForm($form, ['project[name]' => $name]);

        return $response;
    }
}
