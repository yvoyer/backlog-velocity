<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Template;

use Doctrine\Tests\Common\Annotations\Fixtures\Annotation\Template;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BlockView
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Template
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
     *
     * @return Template
     */
    public function renderHeaderTemplate($text)
    {
        $this->output->writeln("<info>{$text}</info>");
    }

    /**
     * @param array $elements
     *
     * @return Template
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
 