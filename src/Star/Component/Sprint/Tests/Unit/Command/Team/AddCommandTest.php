<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Team;

use Star\Component\Sprint\Command\Team\AddCommand;
use Star\Component\Sprint\Entity\Factory\EntityCreatorInterface;
use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Symfony\Component\Console\Helper\HelperSet;

/**
 * Class AddCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Command\Team
 *
 * @covers Star\Component\Sprint\Command\Team\AddCommand
 */
class AddCommandTest extends UnitTestCase
{
    /**
     * @param TeamRepository         $repository
     * @param EntityCreatorInterface $creator
     *
     * @return AddCommand
     */
    private function getCommand(
        TeamRepository $repository = null,
        EntityCreatorInterface $creator = null
    ) {
        $repository = $this->getMockTeamRepository($repository);
        $creator    = $this->getMockEntityCreator($creator);

        return new AddCommand($repository, $creator);
    }

    public function testShouldBeACommand()
    {
        $this->assertInstanceOfCommand($this->getCommand(), 'backlog:team:add', 'Add a team');
    }

    public function testShouldHaveANameArgument()
    {
        $this->assertCommandHasArgument($this->getCommand(), 'name');
    }

    public function testShouldUseTheArgumentSuppliedAsTeamName()
    {
        $creator = $this->getMockEntityCreator();
        $creator
            ->expects($this->once())
            ->method('createTeam')
            ->with('teamName')
            ->will($this->returnValue($this->getMockTeam()));

        $content = $this->executeCommand($this->getCommand(null, $creator), array('name' => 'teamName'));
        $this->assertContains('The object was successfully saved.', $content);
    }
}
