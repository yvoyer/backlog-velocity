<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 * @deprecated todo remove in favor of PersonAggregate or keep for PersonDTO?
 * todo Move to the application BoundedContext since it implements Member
 */
class PersonModel implements Person
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @param PersonId $id
     * @param PersonName $name
     */
    public function __construct(PersonId $id, PersonName $name)
    {
        $this->id = $id->toString();
        $this->name = $name->toString();
    }

    /**
     * @return PersonId
     */
    public function getId()
    {
        return PersonId::fromString($this->id);
    }

    public function memberId(): MemberId
    {
        return MemberId::fromString($this->id);
    }

    /**
     * @return PersonName
     */
    public function getName()
    {
        return new PersonName($this->name);
    }

    /**
     * @param string $id
     * @param string $name
     *
     * @return PersonModel
     */
    public static function fromString($id, $name)
    {
        return new self(PersonId::fromString($id), new PersonName($name));
    }
}
