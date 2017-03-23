<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Backlog;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\ProjectName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class BacklogTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Backlog
     */
    private $backlog;

    public function setUp()
    {
        $this->backlog = Backlog::emptyBacklog();
    }

    public function test_should_autogenerate_name_on_multiple_call_when_creating_sprint()
    {
        $this->backlog->createProject($projectId = ProjectId::fromString('name'), new ProjectName('name'));
        $sprint1 = $this->backlog->createSprint($projectId, new \DateTime());
        $sprint2 = $this->backlog->createSprint($projectId, new \DateTime());
        $this->assertSame('Sprint 1', $sprint1->getName());
        $this->assertSame('Sprint 2', $sprint2->getName());
    }
}
