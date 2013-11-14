<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null;

use Star\Component\Sprint\Entity\ObjectManager;

/**
 * Class NullObjectManager
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Null
 *
 * @todo implement instead of extending
 */
class NullObjectManager extends ObjectManager
{
    public function __construct()
    {
        parent::__construct(new NullEntityCreator(), new NullEntityFinder());
    }
}
 