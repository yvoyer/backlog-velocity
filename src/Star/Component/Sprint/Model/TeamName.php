<?php

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Exception\BacklogAssertion;
use Star\Component\Sprint\Exception\InvalidArgumentException;

final class TeamName
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     * @throws InvalidArgumentException
     */
    public function __construct($value)
    {
        BacklogAssertion::string($value, 'Team name "%s" expected to be string, type %s given.');
        if (empty($value)) {
            // todo create NameCantBeEmptyException
            throw new InvalidArgumentException("The name can't be empty.");
        }

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
