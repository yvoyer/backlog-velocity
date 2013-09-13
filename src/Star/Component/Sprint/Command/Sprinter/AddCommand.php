<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Sprinter;

use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Repository\Repository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Sprinter
 *
 * @deprecated Use ObjectCreatorCommand instead
 */
class AddCommand extends Command
{
    /**
     * @var \Star\Component\Sprint\Repository\Repository
     */
    private $repository;

    /**
     * @var \Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory
     */
    private $factory;

    public function __construct(Repository $repository, InteractiveObjectFactory $factory)
    {
        parent::__construct('backlog:sprinter:add');

        $this->repository = $repository;
        $this->factory    = $factory;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setDescription('Add a sprinter');
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|integer null or 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract method is not implemented
     * @see    setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->factory->setup($this->getHelperSet()->get('dialog'), $output);
        $member = $this->factory->createMember();
        $this->repository->add($member);
        $this->repository->save();

        $output->writeln('The object was successfully saved.');
    }
}
