<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;

final class PersonJoinedTeam extends AggregateChanged
{
    /**
     * @return ProjectId
     */
    public function projectId()
    {
        return ProjectId::fromString($this->payload['project_id']);
    }

    /**
     * @return PersonId
     */
    public function personId()
    {
        return PersonId::fromString($this->payload['person_id']);
    }

    /**
     * @return Person
     * @internal Used for relation synchronization
     */
    public function person()
    {
        return $this->payload['person'];
    }

    /**
     * @return TeamId
     */
    public function teamId()
    {
        return TeamId::fromString($this->payload['team_id']);
    }

    /**
     * @param ProjectId $projectId
     * @param Person $person
     * @param TeamId $teamId
     *
     * @return static
     */
    public static function version1(
        ProjectId $projectId,
        Person $person,
        TeamId $teamId
    ) {
        return self::occur(
            $projectId->toString(),
            [
                'person' => $person,
                'person_id' => $person->getId()->toString(),
                'team_id' => $teamId->toString(),
            ]
        );
    }
}
