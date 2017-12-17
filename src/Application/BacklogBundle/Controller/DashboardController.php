<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Star\Component\Sprint\Domain\Projections\AllProjectsProjection;

/**
 * @Route(service="backlog.controllers.dashboard")
 */
class DashboardController extends Controller
{
    /**
     * @var AllProjectsProjection
     */
    private $projects;

    /**
     * @param AllProjectsProjection $projects
     */
    public function __construct(AllProjectsProjection $projects)
    {
        $this->projects = $projects;
    }

    /**
     * @Route("/", name="dashboard")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render(
            'Dashboard/index.html.twig',
            [
                'projects' => $this->projects->allProjects(),
            ]
        );
    }
}
