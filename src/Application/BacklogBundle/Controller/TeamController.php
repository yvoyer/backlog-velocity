<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Prooph\EventStore\Exception\RuntimeException;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\QueryBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Star\Component\Sprint\Application\BacklogBundle\Form\CreateTeamType;
use Star\Component\Sprint\Application\BacklogBundle\Translation\BacklogMessages;
use Star\Component\Sprint\Domain\Handler\Project\CreateTeam;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Port\TeamDTO;
use Star\Component\Sprint\Domain\Query\Project\TeamWithIdentity;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/team/{id}", name="team_show", requirements={ "id"="[a-zA-Z0-9\-]+" })
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(string $id)
    {
        $promise = $this->queryBus->dispatch(
            new TeamWithIdentity(TeamId::fromString($id))
        );

        $team = null;
        $promise->done(function (TeamDTO $_t) use (&$team) {
            $team = $_t;
        });

        return $this->render(
            'Team/show.html.twig',
            [
                'team' => $team,
                'members' => [],
            ]
        );
    }

    /**
     * @Route("/team", name="team_create", methods={"POST", "GET"})
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
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
                $this->generateUrl('team_show', ['id' => $command->teamId()->toString()])
            );
        }

        return $this->render(
            'Team/create.html.twig',
            [
                'form' => $form->createView(),
                'errors' => $form->getErrors(),
            ]
        );
    }
}
