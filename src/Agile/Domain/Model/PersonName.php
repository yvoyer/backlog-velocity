<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogAssertion;
use Star\BacklogVelocity\Common\Model\Attribute;

final class PersonName implements Attribute
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
     * @param PersonName $name
     *
     * @return bool
     */
    public function equals(PersonName $name)
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
     * @return string
     */
    public function attributeName(): string
    {
        return 'person name';
    }

    /**
     * @return string
     */
    public function attributeValue(): string
    {
        return $this->toString();
    }
}
