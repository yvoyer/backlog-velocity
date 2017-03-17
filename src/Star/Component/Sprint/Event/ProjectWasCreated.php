<?php

namespace Star\Component\Sprint\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\ProjectName;

final class ProjectWasCreated extends AggregateChanged
{
    /**
     * @param ProjectId $id
     * @param ProjectName $name
     *
     * @return ProjectWasCreated
     */
    public static function version1(ProjectId $id, ProjectName $name)
    {
        return $event = new self(
            $id->toString(),
            [
                'name' => $name->toString(),
            ]
        );
    }
}
