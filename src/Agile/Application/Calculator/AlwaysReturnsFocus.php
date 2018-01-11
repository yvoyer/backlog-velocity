<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Calculator;

use Star\BacklogVelocity\Agile\Domain\Model\FocusCalculator;
use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
use Star\BacklogVelocity\Agile\Domain\Model\ManDays;
use Star\BacklogVelocity\Agile\Domain\Model\Velocity;

final class AlwaysReturnsFocus implements FocusCalculator
{
    /**
     * @var int
     */
    private $focus;

    /**
     * @param int $focus
     */
    public function __construct(int $focus)
    {
        $this->focus = $focus;
    }

    /**
     * Returns the focus calculation for the $sprint.
     *
     * @param ManDays $manDays
     * @param Velocity $velocity
     *
     * @return FocusFactor
     */
    public function calculate(ManDays $manDays, Velocity $velocity): FocusFactor
    {
        return FocusFactor::fromInt($this->focus);
    }
}
