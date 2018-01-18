<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Calculator;

use Star\BacklogVelocity\Agile\Domain\Model\EstimatedFocusCalculator;
use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;

final class AlwaysReturnsFocus implements EstimatedFocusCalculator
{
    /**
     * @var int
     */
    private $focus;

    public function __construct(int $focus)
    {
        $this->focus = $focus;
    }

    public function calculateEstimatedFocus(TeamId $teamId, \DateTimeInterface $at): FocusFactor
    {
        return FocusFactor::fromInt($this->focus);
    }
}
