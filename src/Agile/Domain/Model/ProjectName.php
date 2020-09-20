<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Star\BacklogVelocity\Common\Model\Attribute;

final class ProjectName implements Attribute
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function toString(): string
    {
        return strval($this->value);
    }

    public function equalsTo(ProjectName $name): bool
    {
        return $name->value === $this->value;
    }

    public function attributeName(): string
    {
        return 'project name';
    }

    public function attributeValue(): string
    {
        return $this->toString();
    }
}
