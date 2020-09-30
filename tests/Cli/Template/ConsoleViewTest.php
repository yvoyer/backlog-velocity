<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Template;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class ConsoleViewTest extends TestCase
{
    /**
     * @var ConsoleView
     */
    private $view;

    /**
     * @var MockObject
     */
    private $output;

	protected function setUp(): void
    {
        $this->output = $this->getMockBuilder(OutputInterface::class)->getMock();
        $this->view = new ConsoleView($this->output);
    }

    public function test_should_render_the_header(): void
    {
        $this->output
            ->expects($this->once())
            ->method('writeln')
            ->with($this->stringContains('<info>message</info>'));

        $this->view->renderHeaderTemplate('message');
    }

    public function test_should_render_the_success(): void
    {
        $this->output
            ->expects($this->once())
            ->method('writeln')
            ->with($this->stringContains('<info>message</info>'));

        $this->view->renderSuccess('message');
    }

    public function test_should_render_the_error(): void
    {
        $this->output
            ->expects($this->once())
            ->method('writeln')
            ->with($this->stringContains('<error>message</error>'));

        $this->view->renderFailure('message');
    }

    public function test_should_render_the_notice(): void
    {
        $this->output
            ->expects($this->once())
            ->method('writeln')
            ->with($this->stringContains('<comment>message</comment>'));

        $this->view->renderNotice('message');
    }

    public function test_should_render_the_list(): void
    {
    	$output = new BufferedOutput();
    	$view = new ConsoleView($output);
    	$view->renderListTemplate(['message1', 'message2']);

    	$string = $output->fetch();
	    $this->assertStringContainsString('* message1', $string);
	    $this->assertStringContainsString('* message2', $string);
    }
}
