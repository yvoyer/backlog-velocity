<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Application\Calculator;
use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
use Star\BacklogVelocity\Agile\Domain\Model\ManDays;
use Star\BacklogVelocity\Agile\Domain\Model\Velocity;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class FocusCalculator implements \Star\BacklogVelocity\Agile\Domain\Model\FocusCalculator
{
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
        throw new \RuntimeException('Should not confuse with actual focus todo remove');
        if (empty($manDays->toInt())) {
            return FocusFactor::fromInt(0);
        }

        return FocusFactor::fromInt((int) (($velocity->toInt() / $manDays->toInt()) * 100));
    }
}
