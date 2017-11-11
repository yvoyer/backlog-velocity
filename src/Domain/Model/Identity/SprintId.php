<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model\Identity;

use Rhumsaa\Uuid\Uuid;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class SprintId
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    private function __construct($value)
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
}
