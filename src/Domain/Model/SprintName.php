<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Domain\Exception\BacklogAssertion;

final class SprintName
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
        BacklogAssertion::string($value, 'Sprint name "%s" expected to be string, type %s given.');
        BacklogAssertion::notEmpty($value, 'Sprint name "%s" is empty, but non empty value was expected.');
        $this->value = $value;
    }

    /**
     * @param SprintName $name
     *
     * @return bool
     */
    public function equalsTo(SprintName $name)
    {
        return $this->toString() === $name->toString();
    }

    /**
     * @return string
     */
    public function toString()
    {
        return strval($this->value);
    }

    /**
     * @return SprintName
     */
    public static function fixture()
    {
        return new self(uniqid('name-'));
    }
}
