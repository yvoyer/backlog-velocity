<?php

namespace Star\Component\Sprint\Model\Identity;

use Assert\Assertion;

final class ProjectId
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        Assertion::string($id, 'Project id "%s" expected to be string, type %s given.');
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return strval($this->id);
    }
}
