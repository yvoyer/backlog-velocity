<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Prooph\ServiceBus\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Star\Component\Sprint\Application\BacklogBundle\Form\CommitToSprintType;
use Star\Component\Sprint\Application\BacklogBundle\Form\DataClass\CommitmentDataClass;
use Star\Component\Sprint\Application\BacklogBundle\Translation\BacklogMessages;
use Star\Component\Sprint\Domain\Handler\Sprint\CommitMemberToSprint;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="backlog.controllers.commitments")
 */
final class CommitmentController extends Controller
{
    /**
     * @var CommandBus
     */
    private $handlers;

    /**
     * @var BacklogMessages
     */
    private $messages;

    /**
     * @param CommandBus $handlers
     * @param BacklogMessages $messages
     */
    public function __construct(CommandBus $handlers, BacklogMessages $messages)
    {
        $this->handlers = $handlers;
        $this->messages = $messages;
    }

    /**
     * @Route("/commitments", name="sprint_commit", methods={"POST"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commitAction(Request $request)
    {
        $form = $this->createForm(
            CommitToSprintType::class,
            new CommitmentDataClass(
                $request->get('commitment')['memberId'],
                $sprintId = $request->get('commitment')['sprintId'],
                ''
            )
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $request->getMethod() === 'POST') {
            /**
             * @var CommitmentDataClass $data
             */
            $data = $form->getData();

            try {
                $this->handlers->dispatch(
                    CommitMemberToSprint::fromString(
                        $data->sprintId,
                        $data->memberId,
                        $data->manDays
                    )
                );

                $this->messages->addSuccess('flash.success.commitments.created', []);
            } catch (\Throwable $e) {
                $this->messages->addWarning($e->getMessage());
            }
        }

        return new RedirectResponse($this->generateUrl('sprint_show', ['sprintId' => $sprintId]));
    }
}
