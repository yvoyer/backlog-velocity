<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command;

use Star\Component\Sprint\Command\ObjectCreatorCommand;
use Star\Component\Sprint\Entity\Factory\EntityCreatorInterface;
use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Entity\Member;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Symfony\Component\Console\Helper\HelperSet;

/**
 * Class ObjectCreatorCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Command
 *
 * @covers Star\Component\Sprint\Command\ObjectCreatorCommand
 */
class ObjectCreatorCommandTest extends UnitTestCase
{
    /**
     * @param Repository               $repository
     * @param InteractiveObjectFactory $factory
     *
     * @return ObjectCreatorCommand
     */
    private function getCommand(Repository $repository = null, InteractiveObjectFactory $factory = null)
    {
        $repository = $this->getMockRepository($repository);
        $factory    = $this->getMockInteractiveObjectFactory($factory);

        return new ObjectCreatorCommand('commandName', 'type', $repository, $factory);
    }

    public function testShouldBeACommand()
    {
        $this->assertInstanceOfCommand($this->getCommand(), 'commandName', 'Add an object');
    }

    /**
     * @depends testShouldBeACommand
     */
    public function testShouldPersistTheObjectTypeInRepository()
    {
        $object       = $this->getMockEntity();
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
            ->method('createObject')
            ->with('type')
            ->will($this->returnValue($object));

        $repository = $this->getMockRepository();
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($object);
        $repository
            ->expects($this->once())
            ->method('save');

        $command = $this->getCommand($repository, $factory);
        $command->setHelperSet($helperSet);
        $command->run($input, $output);
    }
}
