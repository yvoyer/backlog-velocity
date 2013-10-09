<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Sprinter;

use Star\Component\Sprint\Command\Sprinter\AddCommand;
use Star\Component\Sprint\Entity\Factory\EntityCreatorInterface;
use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;

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
     * @param SprinterRepository     $repository
     * @param EntityCreatorInterface $factory
     *
     * @return AddCommand
     */
    private function getCommand(SprinterRepository $repository = null, EntityCreatorInterface $factory = null)
    {
        $repository = $this->getMockSprinterRepository($repository);
        $factory    = $this->getMockEntityCreator($factory);

        return new AddCommand($repository, $factory);
    }

    public function testShouldBeACommand()
    {
        $this->assertInstanceOfCommand($this->getCommand(), 'backlog:sprinter:add', 'Add a sprinter');
    }

    public function testShouldHaveANameOption()
    {
        $this->assertCommandHasOption($this->getCommand(), 'name');
    }

    public function testShouldPersistTheSprinterFromSuppliedOption()
    {
        $name     = uniqid('name');
        $sprinter = $this->getMockSprinter();

        $factory = $this->getMockEntityCreator();
        $factory
            ->expects($this->once())
            ->method('createSprinter')
            ->with($name)
            ->will($this->returnValue($sprinter));

        $repository = $this->getMockSprinterRepository();
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($sprinter);
        $repository
            ->expects($this->once())
            ->method('save');

        $input = array(
            '--' . 'name' => $name,
        );
        $command = $this->getCommand($repository, $factory);
        $this->executeCommand($command, $input);
    }
}
