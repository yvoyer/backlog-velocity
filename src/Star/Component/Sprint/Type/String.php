<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Type;

use Star\Component\Sprint\Exception\InvalidArgumentException;

/**
 * Class StringId
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Type
 */
class String
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     *
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     */
    public function __construct($value)
    {
        if (false === is_string($value) || empty($value)) {
            throw new InvalidArgumentException('The value should be a non empty string.');
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->value);
    }
}
 