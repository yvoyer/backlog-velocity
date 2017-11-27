<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Prooph\ServiceBus\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Domain\Handler\CreateSprint;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/sprint/{sprintId}", name="sprint_create", methods={"GET"}, requirements={ "sprintId"="[a-zA-Z0-9\-]+" })
     *
     * @param $sprintId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showSprintAction($sprintId)
    {
        return $this->render(
            'Sprint/show.html.twig',
            [
                'sprint' => $this->sprints->getSprintWithIdentity(SprintId::fromString($sprintId)),
            ]
        );
    }

    /**
     * @Route("/sprint/{projectId}", name="sprint_create", methods={"POST"}, requirements={ "projectId"="[a-zA-Z0-9\-]+" })
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction($projectId)
    {
        $this->bus->dispatch(new CreateSprint(ProjectId::fromString($projectId), $sprintId = SprintId::uuid()));

        return new RedirectResponse($this->generateUrl('sprint_show', ['sprintId' => $sprintId->toString()]));
    }
}
