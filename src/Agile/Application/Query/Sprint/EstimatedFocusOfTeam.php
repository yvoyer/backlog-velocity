<?php //declare(strict_types=1);
//
//namespace Star\BacklogVelocity\Agile\Application\Query\Sprint;
//
//use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
//use Star\BacklogVelocity\Common\Application\Query;
//
//final class EstimatedFocusOfTeam extends Query
//{
//    /**
//     * @var TeamId
//     */
//    private $teamId;
//
//    /**
//     * @var \DateTimeInterface
//     */
//    private $at;
//
//    /**
//     * @param TeamId $teamId
//     * @param \DateTimeInterface $at
//     */
//    public function __construct(TeamId $teamId, \DateTimeInterface $at)
//    {
//        $this->teamId = $teamId;
//        $this->at = $at;
//    }
//
//    /**
//     * @return TeamId
//     */
//    public function teamId(): TeamId
//    {
//        return $this->teamId;
//    }
//
//    /**
//     * @return \DateTimeInterface
//     */
//    public function at(): \DateTimeInterface
//    {
//        return $this->at;
//    }
//}
