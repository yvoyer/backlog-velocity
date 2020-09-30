<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Controller;

use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\QueryBus;
use Star\BacklogVelocity\Agile\Application\Command\Sprint\CloseSprint;
use Star\BacklogVelocity\Agile\Application\Command\Sprint\CreateSprint;
use Star\BacklogVelocity\Agile\Application\Command\Sprint\StartSprint;
use Star\BacklogVelocity\Agile\Application\Query\Project\AllMembersOfTeam;
use Star\BacklogVelocity\Agile\Application\Query\Sprint\CommitmentsOfSprint;
use Star\BacklogVelocity\Agile\Application\Query\Sprint\MostActiveSprintInProject;
use Star\BacklogVelocity\Agile\Application\Query\Sprint\SprintWithIdentity;
use Star\BacklogVelocity\Agile\Application\Query\SprintDTO;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\CloseSprintType;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\CreateSprintType;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\DataClass\SprintVelocityDataClass;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\DataClass\CreateSprintDataClass;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\StartSprintType;
use Star\BacklogVelocity\Bundle\BacklogBundle\Translation\BacklogMessages;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    public function activeSprintOfProject(string $projectId) // todo of Team
    {
        $promise = $this->queries->dispatch(MostActiveSprintInProject::fromString($projectId));
        $sprint = null;
        $promise->done(function (SprintDTO $dto) use (&$sprint) {
            $sprint = $dto;
        });
        $promise->done(function (SprintDTO $dto) use (&$sprint) {
            $sprint = $dto;
        });

        return $this->render(
            'BacklogBundle:Dashboard:_sprintPartial.html.twig',
            [
                'projectId' => $projectId,
                'sprint' => $sprint,
            ]
        );
    }

    /**
     * @Route("/sprint/{sprintId}", name="sprint_show", methods={"GET"}, requirements={ "sprintId"="[a-zA-Z0-9\-]+" })
     *
     * @param string $sprintId
     *
     * @return Response
     */
    public function showSprintAction(string $sprintId) :Response
    {
        /**
         * @var SprintDTO $sprint
         */
        $sprint = null;
        $this->queries
            ->dispatch(SprintWithIdentity::fromString($sprintId))
            ->done(function (SprintDTO $data) use (&$sprint) {
                $sprint = $data;
            });

        $commitments = [];
        $this->queries
            ->dispatch(CommitmentsOfSprint::fromString($sprintId))
            ->done(function (array $data) use (&$commitments) {
                $commitments = $data;
            });

        $members = [];
        $this->queries
            ->dispatch(new AllMembersOfTeam($sprint->team->teamId()))
            ->done(function(array $data) use (&$members) {
                $members = $data;
            });

        return $this->render(
            'BacklogBundle:Sprint:show.html.twig',
            [
                'sprint' => $sprint,
                'commitments' => $commitments,
                'members' => $members,
            ]
        );
    }

    /**
     * @Route("/sprint", name="sprint_create", methods={"POST"})
     *
     * @return Response
     */
    public function createAction(Request $request) :Response
    {
        $form = $this->createForm(CreateSprintType::class, new CreateSprintDataClass());
        $form->handleRequest($request);

        if ($form->isValid() && $request->isMethod('POST') && $form->isSubmitted()) {
            /**
             * @var CreateSprintDataClass $data
             */
            $data = $form->getData();
            $sprintId = SprintId::uuid(); // todo use IdGenerator->sprintId()
            $this->handlers->dispatch(
                CreateSprint::fromString($sprintId->toString(), $data->project, $data->team)
            );

            $this->messages->addSuccess('flash.success.sprint.create');

            return new RedirectResponse($this->generateUrl('sprint_show', ['sprintId' => $sprintId->toString()]));
        }

        return new Response(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @Route("/sprint/{sprintId}", name="sprint_start", methods={"PUT"}, requirements={ "sprintId"="[a-zA-Z0-9\-]+" })
     *
     * @param string $sprintId
     * @param Request $request
     *
     * @return Response
     */
    public function startAction(string $sprintId, Request $request) :Response
    {
        $data = new SprintVelocityDataClass();
        $data->sprintId = $sprintId;

        $form = $this->createForm(StartSprintType::class, $data);
        $form->handleRequest($request);

        if ($form->isValid() && $request->isMethod('PUT') && $form->isSubmitted()) {
            try {
                $this->handlers->dispatch(StartSprint::fromString($data->sprintId, $data->velocity));
                $this->messages->addSuccess('flash.success.sprint.started', ['<velocity>' => $data->velocity]);
            } catch (\Throwable $e) {
                $this->messages->addWarning($e->getMessage());
            }
        } else {
            foreach ($form->getErrors(true, true) as $key => $error) {
                $this->messages->addWarning($error->getMessage());
            }
        }

        return new RedirectResponse($this->generateUrl('sprint_show', ['sprintId' => $sprintId]));
    }

    /**
     * @Route("/sprint/{sprintId}", name="sprint_end", methods={"PATCH"}, requirements={ "sprintId"="[a-zA-Z0-9\-]+" })
     *
     * @param string $sprintId
     * @param Request $request
     *
     * @return Response
     */
    public function endAction(string $sprintId, Request $request) :Response
    {
        $data = new SprintVelocityDataClass();
        $data->sprintId = $sprintId;

        $form = $this->createForm(CloseSprintType::class, $data);
        $form->handleRequest($request);

        if ($form->isValid() && $request->isMethod('PATCH') && $form->isSubmitted()) {
            try {
                $this->handlers->dispatch(CloseSprint::fromString($data->sprintId, $data->velocity));
                $this->messages->addSuccess('flash.success.sprint.ended', ['<velocity>' => $data->velocity]);
            } catch (\Throwable $e) {
                $this->messages->addWarning($e->getMessage());
            }
        } else {
            foreach ($form->getErrors(true, true) as $key => $error) {
                $this->messages->addWarning($error->getMessage());
            }
        }

        return new RedirectResponse($this->generateUrl('sprint_show', ['sprintId' => $sprintId]));
    }
}
