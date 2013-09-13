<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command;

use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Repository\Repository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ObjectCreatorCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command
 */
class ObjectCreatorCommand extends Command
{
    /**
     * Possible values: see InteractiveObjectFactory::TYPE_*
     *
     * @var string
     */
    private $type;

    /**
     * @var InteractiveObjectFactory
     */
    private $factory;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @param null|string              $name
     * @param string                   $type
     * @param Repository               $repository
     * @param InteractiveObjectFactory $factory
     */
    public function __construct($name, $type, Repository $repository, InteractiveObjectFactory $factory)
    {
        parent::__construct($name);

        $this->type       = $type;
        $this->repository = $repository;
        $this->factory    = $factory;
    }

    /**
     * Configure the command.
     */
    public function configure()
    {
        $this->setDescription('Add an object');
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

        $object = $this->factory->createObject($this->type);
        $this->repository->add($object);
        $this->repository->save();

        $output->writeln('The object was successfully saved.');
    }
}
