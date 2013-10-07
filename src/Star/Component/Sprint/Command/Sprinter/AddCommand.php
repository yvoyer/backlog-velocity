<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Sprinter;

use Star\Component\Sprint\Entity\Factory\EntityCreatorInterface;
use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Sprinter
 */
class AddCommand extends Command
{
    const OPTION_NAME = 'name';

    /**
     * @var SprinterRepository
     */
    private $repository;

    /**
     * @var EntityCreatorInterface
     */
    private $factory;

    public function __construct(SprinterRepository $repository, EntityCreatorInterface $factory)
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
        $this->addOption(
            self::OPTION_NAME,
            null,
            InputOption::VALUE_OPTIONAL,
            'The name of the sprinter to create'
        );
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
        $name = $input->getOption(self::OPTION_NAME);
        if (empty($name) && $this->factory instanceof InteractiveObjectFactory) {
            $this->factory->setOutput($output);
        }

        $sprinter = $this->factory->createSprinter($name);
        $this->repository->add($sprinter);
        $this->repository->save();

        $output->writeln('The object was successfully saved.');
    }
}
