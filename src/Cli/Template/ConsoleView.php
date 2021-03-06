<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Template;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 * todo Move to Application/Cli
 */
class ConsoleView
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param string $text
     */
    public function renderHeaderTemplate($text)
    {
        $this->output->writeln("<info>{$text}</info>");
    }

    /**
     * @param array $elements
     */
    public function renderListTemplate(array $elements)
    {
        foreach ($elements as $row) {
            $this->output->writeln("  * <comment>{$row}</comment>");
        }
    }

    /**
     * @param string $message
     */
    public function renderSuccess($message)
    {
        $this->output->writeln("<info>{$message}</info>");
    }

    /**
     * @param string $message
     */
    public function renderFailure($message)
    {
        $this->output->writeln("<error>{$message}</error>");
    }

    /**
     * @param string $message
     */
    public function renderNotice($message)
    {
        $this->output->writeln("<comment>{$message}</comment>");
    }
}
