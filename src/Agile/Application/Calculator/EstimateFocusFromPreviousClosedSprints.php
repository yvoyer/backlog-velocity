<?php //declare(strict_types=1);
//
//namespace Star\BacklogVelocity\Agile\Application\Calculator;
//
//use Star\BacklogVelocity\Agile\Domain\Model\EstimatedFocusCalculator;
//use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
//use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
//use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
//
//final class EstimateFocusFromPreviousClosedSprints implements EstimatedFocusCalculator
//{
//    /**
//     * @var SprintRepository
//     */
//    private $sprints;
//
//    public function __construct(SprintRepository $sprints)
//    {
//        $this->sprints = $sprints;
//    }
//
//    public function calculateEstimatedFocus(TeamId $teamId, \DateTimeInterface $at): FocusFactor
//    {
//        return FocusFactor::fromInt(70);
//    }
//}
