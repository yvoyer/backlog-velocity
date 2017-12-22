<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\BacklogVelocity\Agile\Domain\Model\PersonId;
use Star\BacklogVelocity\Agile\Domain\Model\PersonName;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;

final class PersonWasCreated extends AggregateChanged
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
        return PersonId::fromString($this->aggregateId());
    }

    /**
     * @return PersonName
     */
    public function name()
    {
        return new PersonName($this->payload['name']);
    }

    /**
     * @param PersonId $id
     * @param PersonName $name
     * @param ProjectId $projectId
     *
     * @return static
     */
    public static function version1(PersonId $id, PersonName $name, ProjectId $projectId)
    {
        return self::occur(
            $id->toString(),
            [
                'name' => $name->toString(),
                'project_id' => $projectId->toString(),
            ]
        );
    }
}
