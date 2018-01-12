<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Null;

use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
use Star\BacklogVelocity\Agile\Domain\Model\ManDays;
use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\Sprint;
use Star\BacklogVelocity\Agile\Domain\Model\SprintCommitment;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\Velocity;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class NullSprint implements Sprint
{
    public function getId(): SprintId
    {
        return null;
    }

    public function toArray(): array
    {
        return array();
    }

    public function getActualVelocity(): Velocity
    {
        return Velocity::fromInt(0);
    }

    public function getManDays(): ManDays
    {
        return ManDays::fromInt(0);
    }

    public function getName(): SprintName
    {
        return new SprintName('Null sprint');
    }

    public function isValid(): bool
    {
        return false;
    }

    public function isClosed(): bool
    {
        return false;
    }

    public function isStarted(): bool
    {
        return false;
    }

    public function teamId() :TeamId
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function projectId() :ProjectId
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function start(int $estimatedVelocity, \DateTimeInterface $startedAt)
    {
        // Do nothing
    }

    public function close(Velocity $actualVelocity, \DateTimeInterface $closedAt)
    {
        // Do nothing
    }

    public function getFocusFactor(): FocusFactor
    {
        return FocusFactor::fromInt(0);
    }

    public function getEstimatedVelocity(): Velocity
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::getEstimatedVelocity() not implemented yet.');
    }

    public function commit(MemberId $member, ManDays $availableManDays): SprintCommitment
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function getCommitments(): array
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
