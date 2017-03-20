<?php

namespace Star\Component\Sprint\Model\Builder;

use Star\Component\Sprint\BacklogBuilder;
use Star\Component\Sprint\Entity\Sprint;

final class SprintBuilder
{
    /**
     * @var Sprint
     */
    private $sprint;

    /**
     * @var BacklogBuilder
     */
    private $builder;

    /**
     * @param Sprint $sprint
     * @param BacklogBuilder $builder
     */
    public function __construct(Sprint $sprint, BacklogBuilder $builder)
    {
        $this->sprint = $sprint;
        $this->builder = $builder;
    }

    /**
     * @return BacklogBuilder
     */
    public function backlog()
    {
        return $this->builder;
    }

    /**
     * @return \Star\Component\Sprint\Backlog
     */
    public function endBacklog()
    {
        return $this->builder->getBacklog();
    }
}
