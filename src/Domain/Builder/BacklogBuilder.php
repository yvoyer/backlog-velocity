<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Builder;

use Star\Component\Sprint\Domain\Event\ProjectWasCreated;
use Star\Component\Sprint\Domain\Event\TeamWasCreated;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\ProjectName;
use Star\Component\Sprint\Domain\Model\TeamName;

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

    /**
     * @param string $projectName
     * @param string $name
     *
     * @return TeamBuilder
     */
    public static function createTeam(string $projectName, string $name)
    {
        return new TeamBuilder(
            TeamWasCreated::version1(TeamId::fromString($name), new TeamName($name)),
            self::createProject($projectName)
        );
    }
}
