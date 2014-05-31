<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Entity\Id\PersonId;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Exception\InvalidArgumentException;

/**
 * Class PersonModel
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model
 */
class PersonModel implements Person
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var PersonId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     *
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     */
    public function __construct($name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException("The name can't be empty.");
        }

        $this->name = $name;
    }

    /**
     * @return PersonId
     */
    public function getId()
    {
        return $this->id;// = (string) new PersonId($this->name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
 