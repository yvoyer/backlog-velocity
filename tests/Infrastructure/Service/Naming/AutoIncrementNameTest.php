<?php declare(strict_types=1);

namespace Star\Component\Sprint\Infrastructure\Service\Naming;

use PHPUnit\Framework\TestCase;
use Prooph\Common\Event\ProophActionEventEmitter;
use Prooph\ServiceBus\QueryBus;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\SprintModel;
use Star\Component\Sprint\Domain\Model\SprintName;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\SprintCollection;
use Star\Plugin\Null\Entity\NullProject;

final class AutoIncrementNameTest extends TestCase
{
    /**
     * @var AutoIncrementName
     */
    private $strategy;

    /**
     * @var SprintCollection
     */
    private $sprints;

    public function setUp()
    {
        $bus = new QueryBus();
        $emiter = new ProophActionEventEmitter();
        $emiter->attachListener(
            'action_event',//backlog.query.sprints_of_project',
            function () {
            var_dump(func_get_args());
            }
        );
        $bus->setActionEventEmitter($emiter);

        $this->strategy = new AutoIncrementName($bus);
    }

    public function test_it_return_next_name_of_last_sprint()
    {
        $project = new NullProject();
        $this->assertSame('Sprint 1', $this->strategy->nextSprintOfProject(ProjectId::uuid())->toString());
        $this->sprints->saveSprint(SprintModel::pendingSprint(
            SprintId::uuid(), new SprintName('Pending1'), $project->getIdentity(), new \DateTime()
        ));
        $this->assertSame('Sprint 2', $this->strategy->nextSprintOfProject(ProjectId::uuid())->toString());
        $this->sprints->saveSprint(SprintModel::pendingSprint(
            SprintId::uuid(), new SprintName('Pending2'), $project->getIdentity(), new \DateTime()
        ));
        $this->assertSame('Sprint 3', $this->strategy->nextSprintOfProject(ProjectId::uuid())->toString());
    }
}
