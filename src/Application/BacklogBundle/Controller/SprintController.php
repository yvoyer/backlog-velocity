<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Prooph\ServiceBus\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="backlog.controllers.sprint")
 */
final class SprintController extends Controller
{
    /**
     * @var SprintRepository
     */
    private $sprints;

    /**
     * @var CommandBus
     */
    private $bus;

    /**
     * @param SprintRepository $sprints
     * @param CommandBus $bus
     */
    public function __construct(SprintRepository $sprints, CommandBus $bus)
    {
        $this->sprints = $sprints;
        $this->bus = $bus;
    }

    public function activeSprintOfProject($projectId)
    {
        return $this->render(
            'Sprint/activeSprintOfProject.html.twig',
            [
                'projectId' => $projectId,
                'sprint' => $this->sprints->activeSprintOfProject(ProjectId::fromString($projectId)),
            ]
        );
    }

    /**
     * @Route("/sprint/{projectId}", name="sprint_create", methods={"POST"}, requirements={ "projectId"="[a-zA-Z0-9\-]+" })
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
//        $strategy = new AutoIncrementSprintNaming();
//        $name = $strategy->nextSprintName(ProjectId::fromString($projectId));
//
//        $this->sprints->lastSpr
//        if ($request->getMethod() !== 'POST') {
//
//        }
//        $form = $this->createForm(CreateProjectForm::class);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid() && $request->getMethod() === 'POST') {
//            $this->bus->dispatch(new CreateSprint(S$command = $form->getData());
//
//            return new RedirectResponse(
//                $this->generateUrl('project_show', ['id' => $command->projectId()->toString()])
//            );
//        }
//
//        return $this->render(
//            'Project/create.html.twig',
//            [
//                'form' => $form->createView(),
//                'errors' => $form->getErrors(),
//            ]
//        );
    }
}
