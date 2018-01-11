<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

interface FocusCalculator
{
    /**
     * Returns the focus calculation for the $sprint.
     *
     * @param ManDays $manDays
     * @param Velocity $velocity
     *
     * @return FocusFactor
     */
    public function calculate(ManDays $manDays, Velocity $velocity): FocusFactor;
}
