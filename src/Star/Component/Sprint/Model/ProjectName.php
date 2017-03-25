<?php

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Exception\BacklogAssertion;

final class ProjectName
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        BacklogAssertion::string($value, 'Project name "%s" expected to be string, type %s given.');
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return strval($this->value);
    }
}
