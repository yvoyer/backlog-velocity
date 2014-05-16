<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Person;

use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\Repository\MemberRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddPersonCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Person
 */
class AddPersonCommand extends Command
{
    /**
     * The object repository.
     *
     * @var MemberRepository
     */
    private $repository;

    /**
     * @var EntityCreator
     */
    private $creator;

    /**
     * @param MemberRepository $repository
     * @param EntityCreator  $creator
     */
    public function __construct(MemberRepository $repository, EntityCreator $creator)
    {
        parent::__construct('backlog:person:add');
        $this->repository = $repository;
        $this->creator    = $creator;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Add a person.');
        $this->addArgument('name', InputArgument::OPTIONAL, 'The name of the person to add');
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
        $personName = $input->getArgument('name');
        $message = 'The person already exists.';

        if ($this->insert($personName)) {
            $message = 'The person was successfully created.';
        }
        $output->writeln($message);

        /**
         * @var DialogHelper $dialog
         */
        $dialog = $this->getHelper('dialog');
        if ($dialog->askConfirmation($output, '<question>Do you want to add another person?</question>', false)) {
            $this->execute($input, $output);
        }
    }

    /**
     * @param string $teamName
     *
     * @return bool
     */
    private function insert($teamName)
    {
        if (null === $this->repository->findOneByName($teamName)) {
            $team = $this->creator->createSprinter($teamName);var_dump(get_class($this->repository));
            $this->repository->add($team);
            $this->repository->save();
            return true;
        }

        return false;
    }

// todo for testing input
//    public function testExecute()
//    {
//        // ...
//        $commandTester = new CommandTester($command);
//
//        $dialog = $command->getHelper('dialog');
//        $dialog->setInputStream($this->getInputStream('Test\n'));
//        // Equals to a user inputing "Test" and hitting ENTER
//        // If you need to enter a confirmation, "yes\n" will work
//
//        $commandTester->execute(array('command' => $command->getName()));
//
//        // $this->assertRegExp('/.../', $commandTester->getDisplay());
//    }
//
//    protected function getInputStream($input)
//    {
//        $stream = fopen('php://memory', 'r+', false);
//        fputs($stream, $input);
//        rewind($stream);
//
//        return $stream;
//    }
}
 