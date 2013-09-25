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
     * @param TeamRepository           $repository
     * @param InteractiveObjectFactory $factory
     * @param EntityCreatorInterface   $creator
     *
     * @return AddCommand
     */
    private function getCommand(
        TeamRepository $repository = null,
        InteractiveObjectFactory $factory = null,
        EntityCreatorInterface $creator = null
    ) {
        $repository = $this->getMockTeamRepository($repository);
        $factory    = $this->getMockInteractiveObjectFactory($factory);
        $creator    = $this->getMockEntityCreator($creator);

        return new AddCommand($repository, $factory, $creator);
    }

    public function testShouldBeACommand()
    {
        $this->assertInstanceOfCommand($this->getCommand(), 'backlog:team:add', 'Add a team');
    }

    public function testShouldSaveTheInputNameInRepository()
    {
        $team         = $this->getMockEntity();
        $input        = $this->getMockCustom('Symfony\Component\Console\Input\InputInterface');
        $dialogHelper = $this->getMockCustom('Symfony\Component\Console\Helper\DialogHelper', null, false);
        $helperSet    = new HelperSet(array('dialog' => $dialogHelper));

        $output = $this->getMockCustom('Symfony\Component\Console\Output\OutputInterface');
        $output
            ->expects($this->once())
            ->method('writeln')
            ->with('The object was successfully saved.');

        $factory = $this->getMockInteractiveObjectFactory();
        $factory
            ->expects($this->once())
            ->method('setup')
            ->with($dialogHelper, $output);
        $factory
            ->expects($this->once())
            ->method('createTeam')
            ->will($this->returnValue($team));

        $repository = $this->getMockTeamRepository();
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($team);
        $repository
            ->expects($this->once())
            ->method('save');

        $command = $this->getCommand($repository, $factory);
        $command->setHelperSet($helperSet);
        $command->run($input, $output);
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
            ->will($this->returnValue($this->getMockEntity()));

        $content = $this->executeCommand($this->getCommand(null, null, $creator), array('name' => 'teamName'));
        $this->assertContains('The object was successfully saved.', $content);
    }
}
