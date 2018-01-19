<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Twig;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Application\Calculator\NullCalculator;
use Star\BacklogVelocity\Agile\Application\Query\ProjectDTO;
use Star\BacklogVelocity\Agile\Application\Query\SprintDTO;
use Star\BacklogVelocity\Agile\Application\Query\TeamDTO;
use Star\BacklogVelocity\Agile\Domain\Model\SprintStatus;
use Star\BacklogVelocity\Agile\Domain\Model\VelocityCalculator;
use Star\BacklogVelocity\Bundle\BacklogBundle\Translation\BacklogMessages;
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
        $this->extension = new BacklogExtension(
            $this->factory, $this->stack, $this->calculator, BacklogMessages::fixture()
        );
    }

    public function test_it_should_return_a_string_ucfirst()
    {
        $this->assertSame('Some string', $this->extension->ucfirst('some string'));
    }

    public function test_it_should_return_the_current_version()
    {
        $this->assertSame('2.0.0-rc1', $this->extension->version());
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
                    0,
                    new ProjectDTO('id', 'name'),
                    new TeamDTO('id', 'name'),
                    '2000-01-01'
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
        $this->assertSame(0, $this->extension->plannedVelocity('s1'));
    }

    public function test_it_should_return_focus_factor()
    {
        $this->assertSame((float) 0, $this->extension->focusFactor('s1'));
    }

    /**
     * @param string $expected
     * @param string $date
     *
     * @dataProvider provideDatesToFormat
     */
    public function test_it_should_return_the_date_formatted_in_terms_of_days_ago(string $expected, string $date)
    {
        $this->assertSame(
            $expected,
            $this->extension->timeAgo(new \DateTimeImmutable($date), $now = new \DateTime('2000-01-01 00:00:00'))
        );
    }

    public static function provideDatesToFormat()
    {
        return [
            'Should return today' => ['today', '2000-01-01'],
            'Should return today when date has seconds' => ['today', '2000-01-01 00:00:01'],
            'Should return yesterday' => ['yesterday', '1999-12-31'],
            'Should return 2 days ago' => ['2 days ago', '1999-12-30'],
            'Should return 1 month ago' => ['1 month ago', '1999-12-01'],
            'Should return 2 months ago' => ['2 months ago', '1999-11-01'],
            'Should return 1 year when more than 12 months' => ['1 year ago', '1999-01-01'],
            'Should return 2 years' => ['2 years ago', '1998-01-01'],
        ];
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage The date '2000-01-02' cannot be in the future of now '2000-01-01'.
     */
    public function test_it_should_throw_exception_when_date_is_greater_than_now()
    {
        $this->extension->timeAgo(new \DateTimeImmutable('2000-01-02'), $now = new \DateTime('2000-01-01'));
    }
}
