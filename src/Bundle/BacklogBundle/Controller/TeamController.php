<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Controller;

use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\QueryBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Star\BacklogVelocity\Agile\Application\Command\Project\CreateTeam;
use Star\BacklogVelocity\Agile\Application\Query\Project\AllMembersOfTeam;
use Star\BacklogVelocity\Agile\Application\Query\Project\TeamWithIdentity;
use Star\BacklogVelocity\Agile\Application\Query\Sprint\AllSprintsOfTeam;
use Star\BacklogVelocity\Agile\Application\Query\Team\AllMyTeams;
use Star\BacklogVelocity\Agile\Application\Query\TeamDTO;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\CreateTeamType;
use Star\BacklogVelocity\Bundle\BacklogBundle\Translation\BacklogMessages;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(service="backlog.controllers.team")
 */
final class TeamController extends Controller
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var BacklogMessages
     */
    private $messages;

    /**
     * @param CommandBus $commandBus
     * @param QueryBus $queryBus
     * @param BacklogMessages $messages
     */
    public function __construct(CommandBus $commandBus, QueryBus $queryBus, BacklogMessages $messages)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->messages = $messages;
    }

    /**
     * @Route("/team/{teamId}", name="team_show", requirements={ "teamId"="[a-zA-Z0-9\-]+" })
     * @param string $teamId
     * @param Request $request
     *
     * @return Response
     */
    public function getAction(string $teamId, Request $request) :Response
    {
        $teamId = TeamId::fromString($teamId);
        $team = null;
        $this->queryBus
            ->dispatch(new TeamWithIdentity($teamId))
            ->done(function (TeamDTO $result) use (&$team) {
                $team = $result;
            });

        $tab = $request->get('tab', 'sprints');
        $members = [];
        $sprints = [];
        if ('members' === $tab) {
            $tab = 'MembersTab';
            $this->queryBus
                ->dispatch(new AllMembersOfTeam($teamId))
                ->done(function (array $result) use (&$members) {
                    $members = $result;
                });
        }

        if ('sprints' === $tab) {
            $this->queryBus
                ->dispatch(new AllSprintsOfTeam($teamId))
                ->done(function (array $result) use (&$sprints) {
                    $sprints = $result;
                });
            $tab = 'SprintsTab';
        }

        return $this->render(
            "BacklogBundle:Team:show{$tab}.html.twig",
            [
                'team' => $team,
                'members' => $members,
                'sprints' => $sprints,
            ]
        );
    }

    /**
     * @Route("/team", name="team_create", methods={"POST", "GET"})
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request) :Response
    {
        $form = $this->createForm(CreateTeamType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $request->getMethod() === 'POST') {
            /**
             * @var CreateTeam $command
             */
            $this->commandBus->dispatch($command = $form->getData());

            $this->messages->addSuccess(
                'flash.success.team.create',
                [
                    '<name>' => $command->name()->toString(),
                ]
            );

            return new RedirectResponse(
                $this->generateUrl('team_show', ['teamId' => $command->teamId()->toString()])
            );
        }

        return $this->render(
            'BacklogBundle:Team:create.html.twig',
            [
                'form' => $form->createView(),
                'errors' => $form->getErrors(),
            ]
        );
    }

//    /**
//     * @Route(path="/team/{$id}", name="team_name", requirements={ "id"="[a-zA-Z0-9\-]+" }
//     * @param string $id
//     *
//     * @return Response
//     */
//    public function renameAction(string $id, Request $request) :Response
//    {
//        $form = $this->createForm(RenameTeamType::class);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid() && $request->getMethod() === 'PUT') {
//            $this->commandBus->dispatch(
//                $command = RenameTeam::fromString($id, $form->getData())
//            );
//
//            $this->messages->addSuccess(
//                'flash.success.team.rename',
//                [
//                    '<name>' => $command->name()->toString(),
//                ]
//            );
//        }
//
//        return new RedirectResponse(
//            $this->generateUrl('team_show', ['id' => $command->teamId()->toString()])
//        );
//    }

    /**
     * @Route(path="/my-teams", name="my_teams")
     *
     * @return Response
     */
    public function myTeamsAction() :Response
    {
        $promise = $this->queryBus->dispatch(new AllMyTeams());
        $teams = [];
        $promise->done(function (array $_t) use (&$teams) {
            $teams = $_t;
        });

        return $this->render(
            'BacklogBundle:Team:myTeams.html.twig',
            [
                'teams' => $teams,
            ]
        );
    }
}
