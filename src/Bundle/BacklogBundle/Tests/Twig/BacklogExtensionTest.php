<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Twig;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Application\Calculator\NullCalculator;
use Star\BacklogVelocity\Agile\Application\Query\ProjectDTO;
use Star\BacklogVelocity\Agile\Application\Query\SprintDTO;
use Star\BacklogVelocity\Agile\Application\Query\TeamDTO;
use Star\BacklogVelocity\Agile\Domain\Model\SprintStatus;
use Star\BacklogVelocity\Agile\Domain\Model\VelocityCalculator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class BacklogExtensionTest extends TestCase
{
    /**
     * @var FormFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    /**
     * @var VelocityCalculator
     */
    private $calculator;

    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * @var BacklogExtension
     */
    private $extension;

    public function setUp()
    {
        $this->stack = new RequestStack();
        $this->calculator = new NullCalculator();
        $this->factory = $this->createMock(FormFactoryInterface::class);
        $this->extension = new BacklogExtension($this->factory, $this->stack, $this->calculator);
    }

    public function test_it_should_return_a_string_ucfirst()
    {
        $this->assertSame('Some string', $this->extension->ucfirst('some string'));
    }

    public function test_it_should_return_the_current_version()
    {
        $this->assertSame('2.0.0-beta', $this->extension->version());
    }

    public function test_it_should_return_the_badge_for_a_sprint_status()
    {
        $this->assertSame(
            'info',
            $this->extension->sprintStatusBadge(
                $sprintDto = new SprintDTO(
                    'id',
                    'name',
                    SprintStatus::PENDING,
                    0,
                    0,
                    0,
                    new ProjectDTO('id', 'name'),
                    new TeamDTO('id', 'name')
                )
            )
        );
        $sprintDto->status = SprintStatus::STARTED;
        $this->assertSame('warning', $this->extension->sprintStatusBadge($sprintDto));
        $sprintDto->status = SprintStatus::CLOSED;
        $this->assertSame('success', $this->extension->sprintStatusBadge($sprintDto));

        return $sprintDto;
    }

    /**
     * @param SprintDTO $sprint
     * @depends test_it_should_return_the_badge_for_a_sprint_status
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Badge for status 'invalid' is not supported.
     */
    public function test_it_should_throw_exception_when_invalid_status_on_a_sprint_status(SprintDTO $sprint)
    {
        $sprint->status = 'invalid';
        $this->extension->sprintStatusBadge($sprint);
    }

    public function test_it_should_return_estimated_velocity()
    {
        $this->assertSame(0, $this->extension->estimatedVelocity('s1'));
    }

    public function test_it_should_return_focus_factor()
    {
        $this->assertSame((float) 0, $this->extension->focusFactor('s1'));
    }
}
