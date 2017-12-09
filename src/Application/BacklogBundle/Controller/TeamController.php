<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Prooph\EventStore\Exception\RuntimeException;
use Prooph\ServiceBus\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Star\Component\Sprint\Application\BacklogBundle\Form\CreateTeamType;
use Star\Component\Sprint\Application\BacklogBundle\Translation\BacklogMessages;
use Star\Component\Sprint\Domain\Handler\Project\CreateTeam;
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
    private $bus;

    /**
     * @var BacklogMessages
     */
    private $messages;

    /**
     * @param CommandBus $bus
     * @param BacklogMessages $messages
     */
    public function __construct(CommandBus $bus, BacklogMessages $messages)
    {
        $this->bus = $bus;
        $this->messages = $messages;
    }

    /**
     * @Route("/team/{id}", name="team_show", requirements={ "id"="[a-zA-Z0-9\-]+" })
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(string $id)
    {
        throw new RuntimeException(__METHOD__ . ' not implemented');
//        $model = $this->projects->getProjectWithIdentity(ProjectId::fromString($id));

//        return $this->render(
//            'Project/show.html.twig',
//            [
//                'project' => new ProjectDTO($model->getIdentity()->toString(), $model->name()->toString()),
//                'sprints' => array_map(
//                    function (Sprint $sprint) {
//                        return new SprintDTO(
//                            $sprint->getId()->toString(),
//                            $sprint->getName()->toString(),
//                            SprintStatus::fromAggregate($sprint),
//                            -1,
//                            -1,
//                            $sprint->projectId()->toString(),
//                            -1
//                        );
//                    },
//                    $this->sprints->allSprints(new AllObjects())
//                ),
//            ]
//        );
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
            $this->bus->dispatch($command = $form->getData());

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
