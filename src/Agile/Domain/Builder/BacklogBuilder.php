<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Builder;

use Star\BacklogVelocity\Agile\Domain\Model\Event;
use Star\BacklogVelocity\Agile\Domain\Model;

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
            Event\ProjectWasCreated::version1(Model\ProjectId::fromString($name), new Model\ProjectName($name))
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
            Event\TeamWasCreated::version1(Model\TeamId::fromString($name), new Model\TeamName($name)),
            self::createProject($projectName)
        );
    }
}
