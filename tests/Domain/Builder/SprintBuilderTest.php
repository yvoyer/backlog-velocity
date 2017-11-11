<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Builder;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\ManDays;
use Star\Component\Sprint\Domain\Model\SprintModel;
use Star\Component\Sprint\Domain\Model\SprintName;

final class SprintBuilderTest extends TestCase
{
    /**
     * @var SprintBuilder
     */
    private $builder;

    public function setUp()
    {
        $this->builder = new SprintBuilder(
            ProjectBuilder::projectIsCreated('name'),
            SprintId::fromString('s1'),
            ProjectId::fromString('p1'),
            new SprintName('name'),
            new \DateTime('2010-02-03')
        );
    }

    public function test_it_should_create_sprint()
    {
        $sprint = $this->builder->buildSprint();

        $this->assertInstanceOf(SprintModel::class, $sprint);
        $this->assertEquals(ProjectId::fromString('p1'), $sprint->projectId());
        $this->assertEquals(SprintId::fromString('s1'), $sprint->getId());
        $this->assertEquals(new SprintName('name'), $sprint->getName());
        $this->assertFalse($sprint->isStarted());
        $this->assertSame(0, $sprint->getActualVelocity());
        $this->assertEquals(ManDays::fromInt(0), $sprint->getManDays());
        $this->assertSame(0, $sprint->getEstimatedVelocity());
        $this->assertCount(0, $sprint->getCommitments());
    }
}
