<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Controller;

use Prooph\ServiceBus\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Star\BacklogVelocity\Agile\Application\Command\Project\CreateProject;
use Star\BacklogVelocity\Agile\Application\Query\ProjectDTO;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectRepository;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\CreateProjectForm;
use Star\BacklogVelocity\Bundle\BacklogBundle\Translation\BacklogMessages;
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
            'BacklogBundle::Project/show.html.twig',
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
            'BacklogBundle:Project:create.html.twig',
            [
                'form' => $form->createView(),
                'errors' => $form->getErrors(),
            ]
        );
    }
}
