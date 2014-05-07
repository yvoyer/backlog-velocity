<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Id;

use Star\Component\Sprint\Type\String;

/**
 * Class TeamId
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Id
 */
class TeamId
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->value = new String($name);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->value);
    }
}
 