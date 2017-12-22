<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogAssertion;
use Star\BacklogVelocity\Common\Model\Attribute;

final class ProjectName implements Attribute
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

    /**
     * @param ProjectName $name
     *
     * @return bool
     */
    public function equalsTo(ProjectName $name) :bool
    {
        return $name->value === $this->value;
    }

    /**
     * @return string
     */
    public function attributeName(): string
    {
        return 'project name';
    }

    /**
     * @return string
     */
    public function attributeValue(): string
    {
        return $this->toString();
    }
}
