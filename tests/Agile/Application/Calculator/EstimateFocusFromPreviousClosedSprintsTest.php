<?php //declare(strict_types=1);
//
//namespace Star\BacklogVelocity\Agile\Application\Calculator;
//
//use PHPUnit\Framework\TestCase;
//use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
//use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
//use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\SprintCollection;
//
//final class EstimateFocusFromPreviousClosedSprintsTest extends TestCase
//{
//    /**
//     * @var EstimateFocusFromPreviousClosedSprints
//     */
//    private $service;
//
//    /**
//     * @var SprintCollection
//     */
//    private $sprints;
//
//    public function setUp()
//    {
//        $this->sprints = new SprintCollection();
//        $this->service = new EstimateFocusFromPreviousClosedSprints($this->sprints);
//    }
//
//    public function test_it_should_return_the_default_value_when_no_sprint_exists()
//    {
//        $focus = $this->service->calculateEstimatedFocus(new TeamId('t'));
//        $this->assertInstanceOf(FocusFactor::class, $focus);
//        $this->assertSame(70, $focus->toInt());
//    }
//
//    public function test_TOOD()
//    {
//        $this->fail('Do whehn 1, 2, 3, started and pending');
//    }
//}
