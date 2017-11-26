<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @param SprintRepository $sprints
     */
    public function __construct(SprintRepository $sprints)
    {
        $this->sprints = $sprints;
    }

    public function activeSprintOfProject($projectId)
    {
        return $this->render(
            'Sprint/activeSprintOfProject.html.twig',
            [
                'sprint' => $this->sprints->activeSprintOfProject(ProjectId::fromString($projectId)),
            ]
        );
    }
}
