<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Cli\Commands\Adapter\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\MigrationsBundle\Command\MigrationsMigrateDoctrineCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

final class UpdateApplicationCommand extends Command
{
    public function __construct()
    {
        parent::__construct('update');
    }

    protected function configure()
    {
        $this->addOption(
            'uninstall',
            'u',
            InputOption::VALUE_NONE,
            'Whether to install or uninstall the app.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installation = new Application(new \AppKernel('dev', true));
        $installation->addCommands(
            [
                new MigrationsMigrateDoctrineCommand(),
                new DropDatabaseDoctrineCommand(),
            ]
        );

        $command = 'd:m:m';
        $options = ['-n'];
        if ($input->getOption('uninstall')) {
            $command = 'd:d:d';
            $options = ['--force' => true];
        }

        $return = $installation->run(
            new ArrayInput(
                array_merge(
                    [
                        'command' => $command
                    ],
                    $options
                )
            ),
            new NullOutput()
        );

        // todo check why this is never executed
        $output->writeln('The application was installed successfully.');

        return $return;
    }
}
