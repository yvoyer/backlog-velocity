<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Sprinter;

use Star\Component\Sprint\Command\Sprinter\AddCommand;
use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Symfony\Component\Console\Helper\HelperSet;

/**
 * Class AddCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Command\Sprinter
 *
 * @covers Star\Component\Sprint\Command\Sprinter\AddCommand
 */
class AddCommandTest extends UnitTestCase
{
    /**
     * @param \Star\Component\Sprint\Repository\Repository                   $repository
     * @param \Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory $factory
     *
     * @return AddCommand
     */
    private function getCommand(Repository $repository = null, InteractiveObjectFactory $factory = null)
    {
        $repository = $this->getMockRepository($repository);
        $factory    = $this->getMockInteractiveObjectFactory($factory);

        return new AddCommand($repository, $factory);
    }

    public function testShouldBeACommand()
    {
        $this->assertInstanceOfCommand($this->getCommand(), 'backlog:sprinter:add', 'Add a sprinter');
    }

    public function testShouldPersistTheInputSprinterInRepository()
    {
        $sprinter     = $this->getMockEntity();
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
            ->method('createSprinter')
            ->will($this->returnValue($sprinter));

        $repository = $this->getMockSprinterRepository();
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($sprinter);
        $repository
            ->expects($this->once())
            ->method('save');

        $command = $this->getCommand($repository, $factory);
        $command->setHelperSet($helperSet);
        $command->run($input, $output);
    }
}
