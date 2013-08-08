<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Stub\Entity;

use Star\Component\Sprint\Entity\IdentifierInterface;

/**
 * Class StubIdentifier
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Stub\Entity
 */
class StubIdentifier implements IdentifierInterface
{
    /**
     * @var mixed
     */
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Returns the key.
     *
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }
}
