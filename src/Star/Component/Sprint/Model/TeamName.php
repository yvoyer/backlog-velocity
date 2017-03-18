<?php

namespace Star\Component\Sprint\Model;

use Assert\Assertion;

final class TeamName
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
        Assertion::string($value, 'Team name "%s" expected to be string, type %s given.');
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
