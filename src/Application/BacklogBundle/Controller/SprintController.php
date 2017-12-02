<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\QueryBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Star\Component\Sprint\Application\BacklogBundle\Translation\BacklogMessages;
use Star\Component\Sprint\Domain\Handler\CreateSprint;
use Star\Component\Sprint\Domain\Handler\Sprint\StartSprint;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Port\SprintDTO;
use Star\Component\Sprint\Domain\Port\TeamMemberDTO;
use Star\Component\Sprint\Domain\Query\Sprint as Query;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="backlog.controllers.sprint")
 */
final class SprintController extends Controller
{
    /**
     * @var CommandBus
     */
    private $handlers;

    /**
     * @var QueryBus
     */
    private $queries;

    /**
     * @var BacklogMessages
     */
    private $messages;

    /**
     * @param CommandBus $handlers
     * @param QueryBus $queries
     * @param BacklogMessages $messages
     */
    public function __construct(CommandBus $handlers, QueryBus $queries, BacklogMessages $messages)
    {
        $this->handlers = $handlers;
        $this->queries = $queries;
        $this->messages = $messages;
    }

    public function activeSprintOfProject(string $projectId)
    {
        $promise = $this->queries->dispatch(Query\MostActiveSprintInProject::fromString($projectId));
        $sprint = null;
        $promise->done(function (SprintDTO $dto) use (&$sprint) {
            $sprint = $dto;
        });

        return $this->render(
            'Dashboard/_sprintPartial.html.twig',
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
        /**
         * @var SprintDTO $sprint
         */
        $sprint = null;
        $promise->done(function ($dto) use (&$sprint) {
            $sprint = $dto;
        });

        $promise = $this->queries->dispatch(Query\CommitmentsOfSprint::fromString($sprintId));
        $commitments = null;
        $promise->done(function (array $dto) use (&$commitments) {
            $commitments = $dto;
        });

        return $this->render(
            'Sprint/show.html.twig',
            [
                'sprint' => $sprint,
//                'commitments' => $commitments,
                'members' => [
                    TeamMemberDTO::fromString('john-id', 'John', 'team-id-1', 'Team name 1'),
                    TeamMemberDTO::fromString('jane-id', 'Jane', 'team-id-2', 'Team name 2'),
                ],
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

        $this->messages->addSuccess('flash.success.sprint.create');

        return new RedirectResponse($this->generateUrl('sprint_show', ['sprintId' => $sprintId->toString()]));
    }

    /**
     * @Route("/sprint/{sprintId}", name="sprint_start", methods={"PUT"}, requirements={ "sprintId"="[a-zA-Z0-9\-]+" })
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function startAction(string $sprintId, Request $request)
    {
        // todo Add form validation


        try {
            $this->handlers->dispatch(
                new StartSprint(SprintId::fromString($sprintId), (int) $request->get('velocity'))
            );
            $this->messages->addSuccess('flash.success.sprint.started', []);
        } catch (\Throwable $e) {
            $this->messages->addWarning($e->getMessage());
        }

        return new RedirectResponse($this->generateUrl('sprint_show', ['sprintId' => $sprintId]));
    }
}
