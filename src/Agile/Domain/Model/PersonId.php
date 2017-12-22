<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Behat\Transliterator\Transliterator;
use Rhumsaa\Uuid\Uuid;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogAssertion;
use Star\Component\Identity\Identity;

// todo move to other BoundContext
final class PersonId implements Identity
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
     * Returns the entity class for the identity.
     *
     * @return string
     */
    public function entityClass()
    {
        return Person::class;
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
