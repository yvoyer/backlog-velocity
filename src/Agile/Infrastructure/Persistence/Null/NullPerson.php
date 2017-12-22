<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Null;

use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Model\Person;
use Star\BacklogVelocity\Agile\Domain\Model\PersonId;
use Star\BacklogVelocity\Agile\Domain\Model\PersonName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class NullPerson implements Person
{
    /**
     * @var PersonId
     */
    private $id;

    public function __construct(string $id = null)
    {
        if (! $id) {
            $id = PersonId::uuid()->toString();
        }

        $this->id = PersonId::fromString($id);
    }

    /**
     * @return PersonId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PersonName
     */
    public function getName()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @return MemberId
     */
    public function memberId(): MemberId
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
