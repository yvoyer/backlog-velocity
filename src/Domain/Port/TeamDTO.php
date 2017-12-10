<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Port;

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
