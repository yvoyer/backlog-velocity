<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Calculator;

use Star\BacklogVelocity\Agile\Domain\Model\EstimatedFocusCalculator;
use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;

final class EstimateFocusFromPreviousClosedSprints implements EstimatedFocusCalculator
{
    /**
     * @var SprintRepository
     */
    private $sprints;

    /**
     * @param SprintRepository $sprints
     */
    public function __construct(SprintRepository $sprints)
    {
        $this->sprints = $sprints;
    }

    /**
     * Estimated focus is used on Sprint creation.
     *
     * @param TeamId $teamId
     *
     * @return FocusFactor
     */
    public function calculateEstimatedFocus(TeamId $teamId): FocusFactor
    {
        return FocusFactor::fromInt(70);
    }
}
