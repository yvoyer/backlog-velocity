<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Controller;

use Prooph\ServiceBus\QueryBus;
use Star\BacklogVelocity\Agile\Application\Query\Project\AllProjects;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(service="backlog.controllers.dashboard")
 */
class DashboardController extends Controller
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @param QueryBus $projects
     */
    public function __construct(QueryBus $projects)
    {
        $this->queryBus = $projects;
    }

    /**
     * @Route("/", name="dashboard")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $promise = $this->queryBus->dispatch(new AllProjects());
        $projects = [];
        $promise->done(function ($data) use (&$projects) {
            $projects = $data;
        });

        return $this->render(
            'BacklogBundle:Dashboard:index.html.twig',
            [
                'projects' => $projects,
            ]
        );
    }
}
