<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Template;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class ConsoleViewTest extends TestCase
{
    /**
     * @var ConsoleView
     */
    private $view;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $output;

    public function setUp()
    {
        $this->output = $this->getMockBuilder(OutputInterface::class)->getMock();
        $this->view = new ConsoleView($this->output);
    }

    public function test_should_render_the_header()
    {
        $this->output
            ->expects($this->once())
            ->method('writeln')
            ->with($this->stringContains('<info>message</info>'));

        $this->view->renderHeaderTemplate('message');
    }

    public function test_should_render_the_success()
    {
        $this->output
            ->expects($this->once())
            ->method('writeln')
            ->with($this->stringContains('<info>message</info>'));

        $this->view->renderSuccess('message');
    }

    public function test_should_render_the_error()
    {
        $this->output
            ->expects($this->once())
            ->method('writeln')
            ->with($this->stringContains('<error>message</error>'));

        $this->view->renderFailure('message');
    }

    public function test_should_render_the_notice()
    {
        $this->output
            ->expects($this->once())
            ->method('writeln')
            ->with($this->stringContains('<comment>message</comment>'));

        $this->view->renderNotice('message');
    }

    public function test_should_render_the_list()
    {
        $this->output
            ->expects($this->at(0))
            ->method('writeln')
            ->with($this->stringContains('  * <comment>message1</comment>'));
        $this->output
            ->expects($this->at(1))
            ->method('writeln')
            ->with($this->stringContains('  * <comment>message2</comment>'));

        $this->view->renderListTemplate(array('message1', 'message2'));
    }
}
