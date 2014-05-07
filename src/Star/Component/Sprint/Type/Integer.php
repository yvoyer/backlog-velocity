<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Type;

use Star\Component\Sprint\Exception\InvalidArgumentException;

/**
 * Class IntegerId
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Type
 */
class Integer
{
    /**
     * @var int
     */
    private $value;

    /**
     * @param integer $int
     *
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     */
    public function __construct($int)
    {
        if (false == is_int($int)) {
            throw new InvalidArgumentException('The value must be numeric.');
        }
        $this->value = $int;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->value);
    }
}
 