<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Controller;

use Prooph\ServiceBus\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Star\BacklogVelocity\Agile\Application\Command\Sprint\CommitMemberToSprint;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\CommitToSprintType;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\DataClass\CommitmentDataClass;
use Star\BacklogVelocity\Bundle\BacklogBundle\Translation\BacklogMessages;
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

                $this->messages->addSuccess(
                    'flash.success.commitments.created',
                    [
                        '<id>' => $data->memberId,
                        '<man_days>' => $data->manDays,
                    ]
                );
            } catch (\Throwable $e) {
                $this->messages->addWarning($e->getMessage());
            }
        }

        foreach ($form->getErrors(true, true) as $key => $error) {
            $this->messages->addWarning($error->getMessage());
        }

        return new RedirectResponse($this->generateUrl('sprint_show', ['sprintId' => $sprintId]));
    }
}
