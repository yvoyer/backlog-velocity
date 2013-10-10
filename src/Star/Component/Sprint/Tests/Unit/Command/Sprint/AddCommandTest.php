<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Sprint;

use Star\Component\Sprint\Command\Sprint\AddCommand;
use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class AddCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Command\Sprint
 *
 * @covers Star\Component\Sprint\Command\Sprint\AddCommand
 */
class AddCommandTest extends UnitTestCase
{
    /**
     * @param SprintRepository $repository
     * @param EntityCreator    $factory
     *
     * @return AddCommand
     */
    private function getCommand(SprintRepository $repository = null, EntityCreator $factory = null)
    {
        $repository = $this->getMockSprintRepository($repository);
        $factory    = $this->getMockEntityCreator($factory);

        return new AddCommand($repository, $factory);
    }

    public function testShouldBeACommand()
    {
        $this->assertInstanceOfCommand($this->getCommand(), 'backlog:sprint:add', 'Add a sprint');
    }

    /**
     * @depends testShouldBeACommand
     */
    public function testShouldPersistTheInputSprintInRepository()
    {
        $sprint = $this->getMockEntity();
        $input  = $this->getMockCustom('Symfony\Component\Console\Input\InputInterface');

        $output = $this->getMockCustom('Symfony\Component\Console\Output\OutputInterface');
        $output
            ->expects($this->once())
            ->method('writeln')
            ->with('The object was successfully saved.');

        $factory = $this->getMockEntityCreator();
        $factory
            ->expects($this->once())
            ->method('createSprint')
            ->will($this->returnValue($sprint));

        $repository = $this->getMockSprintRepository();
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($sprint);
        $repository
            ->expects($this->once())
            ->method('save');

        $command = $this->getCommand($repository, $factory);
        $command->run($input, $output);
    }
}
