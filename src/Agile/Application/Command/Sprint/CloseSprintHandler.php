<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Sprint;

use Star\BacklogVelocity\Agile\Domain\Model\FocusCalculator;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;

final class CloseSprintHandler
{
    /**
     * @var SprintRepository
     */
    private $sprints;

    /**
     * @var FocusCalculator
     */
    private $calculator;

    /**
     * @param SprintRepository $sprints
     * @param FocusCalculator $calculator
     */
    public function __construct(SprintRepository $sprints, FocusCalculator $calculator)
    {
        $this->sprints = $sprints;
        $this->calculator = $calculator;
    }

    public function __invoke(CloseSprint $command)
    {
        $sprint = $this->sprints->getSprintWithIdentity($command->sprintId());
        $sprint->close(
            $command->actualVelocity(),
            $this->calculator->calculate($sprint->getManDays(), $command->actualVelocity()),
            new \DateTimeImmutable()
        );

        $this->sprints->saveSprint($sprint);
    }
}
