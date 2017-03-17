<?php

namespace Star\Component\Sprint\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\ProjectName;

final class ProjectWasCreated extends AggregateChanged
{
    /**
     * @param string $name
     *
     * @return ProjectWasCreated
     */
    public static function version1($name)
    {
        return $event = new self(
            ProjectId::fromString($name),
            [
                'name' => ProjectName::fromString($name)->toString(),
                'version' => 1,
            ]
        );
    }
}
