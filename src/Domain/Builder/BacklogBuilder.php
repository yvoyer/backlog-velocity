<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Builder;

use Star\Component\Sprint\Domain\Event\ProjectWasCreated;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\ProjectName;

final class BacklogBuilder
{
    /**
     * @param string $name
     *
     * @return ProjectBuilder
     */
    public static function createProject(string $name)
    {
        return new ProjectBuilder(
            ProjectWasCreated::version1(ProjectId::fromString($name), new ProjectName($name))
        );
    }
}
