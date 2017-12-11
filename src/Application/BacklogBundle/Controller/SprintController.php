<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Assert\Assertion;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\QueryBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Star\Component\Sprint\Application\BacklogBundle\Translation\BacklogMessages;
use Star\Component\Sprint\Domain\Handler\CreateSprint;
use Star\Component\Sprint\Domain\Handler\Sprint\StartSprint;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Port\SprintDTO;
use Star\Component\Sprint\Domain\Query\Project as ProjectQuery;
use Star\Component\Sprint\Domain\Query\Sprint as SprintQuery;
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
        $promise = $this->queries->dispatch(SprintQuery\MostActiveSprintInProject::fromString($projectId));
        $sprint = null;
        $promise->done(function (SprintDTO $dto) use (&$sprint) {
            $sprint = $dto;
        });
        $promise->done(function (SprintDTO $dto) use (&$sprint) {
            $sprint = $dto;
        });

        return $this->render(
            'Dashboard/_sprintPartial.html.twig',
            [
                'projectId' => $projectId,
                'sprint' => $sprint,
                'teamId' => 'TODO someteamId',
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
    public function showSprintAction(string $sprintId)
    {
        /**
         * @var SprintDTO $sprint
         */
        $sprint = null;
        $this->queries
            ->dispatch(SprintQuery\SprintWithIdentity::fromString($sprintId))
            ->done(function (SprintDTO $data) use (&$sprint) {
                $sprint = $data;
            });

        $commitments = [];
        $this->queries
            ->dispatch(SprintQuery\CommitmentsOfSprint::fromString($sprintId))
            ->done(function (array $data) use (&$commitments) {
                $commitments = $data;
            });

        $members = [];
        $this->queries
            ->dispatch(new ProjectQuery\AllMembersOfTeam($sprint->teamId()))
            ->done(function(array $data) use (&$members) {
                $members = $data;
            });

        return $this->render(
            'Sprint/show.html.twig',
            [
                'sprint' => $sprint,
                'commitments' => $commitments,
                'members' => $members,
            ]
        );
    }

    /**
     * @Route("/sprint/{projectId}", name="sprint_create", methods={"POST"}, requirements={ "projectId"="[a-zA-Z0-9\-]+" })
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(string $projectId)
    {
        $this->handlers->dispatch(
            CreateSprint::fromString($sprintId = SprintId::uuid()->toString(), $projectId, $teamId)
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
        try {
            Assertion::integerish($request->get('velocity'));

            $this->handlers->dispatch(
                new StartSprint(SprintId::fromString($sprintId), $velocity = (int) $request->get('velocity'))
            );
            $this->messages->addSuccess('flash.success.sprint.started', ['<velocity>' => $velocity]);
        } catch (\Throwable $e) {
            $this->messages->addWarning($e->getMessage());
        }

        return new RedirectResponse($this->generateUrl('sprint_show', ['sprintId' => $sprintId]));
    }
}
