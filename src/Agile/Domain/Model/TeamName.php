<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogAssertion;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\InvalidArgumentException;
use Star\BacklogVelocity\Common\Model\Attribute;

final class TeamName implements Attribute
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

    /**
     * @param TeamName $name
     *
     * @return bool
     */
    public function equals(TeamName $name) :bool
    {
        return $name->toString() === $this->toString();
    }

    /**
     * @return string
     */
    public function attributeName(): string
    {
        return 'name';
    }

    /**
     * @return string
     */
    public function attributeValue(): string
    {
        return $this->toString();
    }
}
