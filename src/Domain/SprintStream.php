<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Domain;

final class SprintStream
{
    /**
     * @var BacklogStream
     */
    private $stream;

    /**
     * @var array
     */
    private $events = [];

    /**
     * @param BacklogStream $stream
     */
    public function __construct(BacklogStream $stream)
    {
        $this->stream = $stream;
    }

    public function memberIsCommitted()
    {

        return $this;
    }

    public function isStarted()
    {

        return $this;
    }

    public function isClosed()
    {

        return $this;
    }

    /**
     * @return \Star\Component\Sprint\Model\ProjectAggregate
     */
    public function getProject()
    {
        return $this->stream->getProject();
    }
}
