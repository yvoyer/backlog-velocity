<?php

namespace Star\Component\Sprint\Domain\Model\Identity;

use Behat\Transliterator\Transliterator;
use Rhumsaa\Uuid\Uuid;
use Star\Component\Sprint\Domain\Exception\BacklogAssertion;

final class PersonId
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    private function __construct($id)
    {
        BacklogAssertion::string($id, 'Person id "%s" expected to be string, type %s given.');
        $this->id = $id;
    }

    /**
     * @param PersonId $id
     *
     * @return bool
     */
    public function matchIdentity(PersonId $id)
    {
        return $this->toString() === $id->toString();
    }

    /**
     * @return string
     */
    public function toString()
    {
        return strval($this->id);
    }

    /**
     * @param string $string
     *
     * @return PersonId
     */
    public static function fromString($string)
    {
        return new self(Transliterator::urlize($string));
    }

    /**
     * @return PersonId
     */
    public static function uuid()
    {
        return self::fromString(Uuid::uuid4()->toString());
    }
}
