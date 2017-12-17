<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Prooph\ServiceBus\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Star\Component\Sprint\Application\BacklogBundle\Form\CreateProjectForm;
use Star\Component\Sprint\Application\BacklogBundle\Translation\BacklogMessages;
use Star\Component\Sprint\Domain\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Domain\Handler\CreateProject;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Port\ProjectDTO;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="backlog.controllers.project")
 */
final class ProjectController extends Controller
{
    /**
     * @var ProjectRepository
     */
    private $projects;

    /**
     * @var SprintRepository
     */
    private $sprints;

    /**
     * @var CommandBus
     */
    private $bus;

    /**
     * @var BacklogMessages
     */
    private $messages;

    /**
     * @param ProjectRepository $projects
     * @param SprintRepository $sprints
     * @param CommandBus $bus
     * @param BacklogMessages $messages
     */
    public function __construct(
        ProjectRepository $projects,
        SprintRepository $sprints,
        CommandBus $bus,
        BacklogMessages $messages
    ) {
        $this->projects = $projects;
        $this->sprints = $sprints;
        $this->bus = $bus;
        $this->messages = $messages;
    }

    /**
     * @Route("/project/{id}", name="project_show", requirements={ "id"="[a-zA-Z0-9\-]+" })
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction($id)
    {
        $model = $this->projects->getProjectWithIdentity(ProjectId::fromString($id));

        return $this->render(
            'Project/show.html.twig',
            [
                'project' => new ProjectDTO($model->getIdentity()->toString(), $model->name()->toString()),
                'sprints' => [], // todo list sprints of project
            ]
        );
    }

    /**
     * @Route("/project", name="project_create", methods={"POST", "GET"})
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CreateProjectForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $request->getMethod() === 'POST') {
            /**
             * @var CreateProject $command
             */
            $this->bus->dispatch($command = $form->getData());

            $this->messages->addSuccess(
                'flash.success.project.create',
                [
                    '<name>' => $command->name()->toString(),
                ]
            );

            return new RedirectResponse(
                $this->generateUrl('project_show', ['id' => $command->projectId()->toString()])
            );
        }

        return $this->render(
            'Project/create.html.twig',
            [
                'form' => $form->createView(),
                'errors' => $form->getErrors(),
            ]
        );
    }
}
