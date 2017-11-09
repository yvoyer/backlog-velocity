<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Domain;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\ProjectAggregate;

final class BacklogStreamTest extends TestCase
{
    public function test_it_should_create_project()
    {
        $project = BacklogStream::projectIsCreated('P 1')->getProject();

        $this->assertInstanceOf(ProjectAggregate::class, $project);
        $this->assertSame('p-1', $project->getIdentity()->toString());
        $this->assertSame('P 1', $project->name()->toString());
    }

    public function test_it_should_create_sprint()
    {
        $project = BacklogStream::projectIsCreated('p1')
            ->withPendingSprint('s1', 'Sprint 1')
            ->getProject();
        $this->assertInstanceOf(ProjectAggregate::class, $project);
        $this->assertCount(1, $project->sprints());
        $this->assertContainsOnlyInstancesOf(SprintId::class, $project->sprints());
    }
}
