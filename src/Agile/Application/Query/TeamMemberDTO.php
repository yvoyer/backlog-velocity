<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query;

final class TeamMemberDTO
{
    /**
     * @var string
     */
    public $personId;

    /**
     * @var string
     */
    public $personName;

    /**
     * @param string $personId
     * @param string $personName
     */
    public function __construct(string $personId, string $personName) {
        $this->personId = $personId;
        $this->personName = $personName;
    }
}
