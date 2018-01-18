<?php //declare(strict_types=1);
//
//namespace Star\BacklogVelocity\Agile\Application\Calculator;
//
//use PHPUnit\Framework\TestCase;
//use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
//use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
//
//final class AlwaysReturnsFocusTest extends TestCase
//{
//    public function test_it_should_always_return_the_same_focus()
//    {
//        $calculator = new AlwaysReturnsFocus(3);
//        $focus1 = $calculator->calculateEstimatedFocus(new TeamId('t'));
//        $this->assertInstanceOf(FocusFactor::class, $focus1);
//
//        $focus2 = $calculator->calculateEstimatedFocus(new TeamId('r'));
//        $this->assertInstanceOf(FocusFactor::class, $focus2);
//        $this->assertSame(3, $focus1->toInt());
//        $this->assertSame(3, $focus2->toInt());
//        $this->assertNotSame($focus1, $focus2, 'Should be different instances');
//    }
//}
