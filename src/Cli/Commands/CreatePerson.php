<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Commands;

use Star\BacklogVelocity\Agile\Application\Command\Project;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityAlreadyExistsException;
use Star\BacklogVelocity\Agile\Domain\Model\PersonRepository;
use Star\BacklogVelocity\Cli\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class CreatePerson extends Command
{
    /**
     * The object repository.
     *
     * @var PersonRepository
     */
    private $repository;

    /**
     * @param PersonRepository $repository
     */
    public function __construct(PersonRepository $repository)
    {
        parent::__construct('backlog:person:add');
        $this->repository = $repository;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Add a person.');
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the person to add');
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
     * @see    setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $view = new ConsoleView($output);
        $personName = $input->getArgument('name');

        try {
            $handler = new Project\CreatePersonHandler($this->repository);
            $handler(Project\CreatePerson::fromString($personName, $personName));
        } catch (EntityAlreadyExistsException $exception) {
            $view->renderFailure("The person '{$personName}' already exists.");
            return 1;
        } catch (\Throwable $exception) {
            $view->renderFailure($exception->getMessage());
            return 1;
        }

        $view->renderSuccess("The person '{$personName}' was successfully saved.");
        return 0;
    }
}
