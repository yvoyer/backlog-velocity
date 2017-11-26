<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Projections;

use Star\Component\Sprint\Domain\Port\ProjectDTO;

interface AllProjectsProjection
{
    /**
     * @return ProjectDTO[]
     */
    public function allProjects();
}
