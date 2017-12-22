<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Rhumsaa\Uuid\Uuid;
use Star\Component\Identity\Identity;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class SprintId implements Identity// todo extends StringIdentity
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->value;
    }

    /**
     * @param SprintId $sprintId
     *
     * @return bool
     */
    public function matchIdentity(SprintId $sprintId) :bool
    {
        return $sprintId->toString() === $this->toString();
    }

    /**
     * @return SprintId
     */
    public static function uuid()
    {
        return new self(Uuid::uuid4()->toString());
    }

    /**
     * @param string $value
     *
     * @return SprintId
     */
    public static function fromString($value)
    {
        return new self($value);
    }

    /**
     * Returns the entity class for the identity.
     *
     * @return string
     */
    public function entityClass()
    {
        return Sprint::class;
    }
}
