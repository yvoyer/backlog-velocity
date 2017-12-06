<?php

namespace Star\Component\Sprint\Domain\Port;

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
