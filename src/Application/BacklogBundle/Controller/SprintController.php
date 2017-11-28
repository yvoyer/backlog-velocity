<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\QueryBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Domain\Handler\CreateSprint;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Query\Sprint as Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @Route(service="backlog.controllers.sprint")
 */
final class SprintController extends Controller
{
    /**
     * @var SprintRepository
     * todo Remove
     */
    private $sprints;

    /**
     * @var CommandBus
     */
    private $handlers;

    /**
     * @var QueryBus
     */
    private $queries;

    /**
     * @param SprintRepository $sprints
     * @param CommandBus $handlers
     * @param QueryBus $queries
     */
    public function __construct(SprintRepository $sprints, CommandBus $handlers, QueryBus $queries)
    {
        $this->sprints = $sprints;
        $this->handlers = $handlers;
        $this->queries = $queries;
    }

    public function activeSprintOfProject(string $projectId)
    {
        $promise = $this->queries->dispatch(Query\MostActiveSprintInProject::fromString($projectId));
        $sprint = null;
        $promise->done(function ($dto) use (&$sprint) {
            $sprint = $dto;
        });

        return $this->render(
            'Sprint/activeSprintOfProject.html.twig',
            [
                'projectId' => $projectId,
                'sprint' => $sprint,
            ]
        );
    }

    /**
     * @Route("/sprint/{sprintId}", name="sprint_show", methods={"GET"}, requirements={ "sprintId"="[a-zA-Z0-9\-]+" })
     *
     * @param $sprintId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showSprintAction($sprintId)
    {
        $promise = $this->queries->dispatch(Query\SprintWithIdentity::fromString($sprintId));
        $sprint = null;
        $promise->done(function ($dto) use (&$sprint) {
            $sprint = $dto;
        });

        return $this->render(
            'Sprint/show.html.twig',
            [
                'sprint' => $sprint,
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
        $this->handlers->dispatch(
            new CreateSprint(ProjectId::fromString($projectId), $sprintId = SprintId::uuid())
        );

        $this->addFlash('success', 'flash.success.sprint.create');

        return new RedirectResponse($this->generateUrl('sprint_show', ['sprintId' => $sprintId->toString()]));
    }
}
