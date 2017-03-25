<?php

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Exception\BacklogAssertion;

final class PersonName
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
        BacklogAssertion::string($value, 'Person name "%s" expected to be string, type %s given.');
        BacklogAssertion::notEmpty($value, 'Person name "%s" is empty, but non empty value was expected.');
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
