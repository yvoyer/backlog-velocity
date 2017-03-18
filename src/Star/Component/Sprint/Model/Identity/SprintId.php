<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model\Identity;

use Star\Component\Sprint\Type\String;

/**
 * Class SprintId
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model\Identity
 */
class SprintId
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = new String($name);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->name);
    }
}
