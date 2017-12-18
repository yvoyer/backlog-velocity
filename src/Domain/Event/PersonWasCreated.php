<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\PersonName;

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
