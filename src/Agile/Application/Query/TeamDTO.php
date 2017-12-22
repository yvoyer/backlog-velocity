<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query;

use Star\BacklogVelocity\Agile\Domain\Model\TeamId;

final class TeamDTO
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @param string $teamId
     * @param string $name
     */
    public function __construct(string $teamId, string $name)
    {
        $this->id = $teamId;
        $this->name = $name;
    }

    public function teamId() :TeamId
    {
        return TeamId::fromString($this->id);
    }

    /**
     * @param string $teamId
     * @param string $name
     *
     * @return TeamDTO
     */
    public static function fromString(string $teamId, string $name) :self
    {
        return new self($teamId, $name);
    }
}
