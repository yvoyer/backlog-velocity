<?php

namespace Star\Component\Sprint\Model;

use Assert\Assertion;

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
        Assertion::string($value, 'Person name "%s" expected to be string, type %s given.');
        Assertion::notEmpty($value, 'Person name "%s" is empty, but non empty value was expected.');
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
