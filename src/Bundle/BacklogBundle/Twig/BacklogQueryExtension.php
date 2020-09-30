<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Twig;

use Prooph\ServiceBus\QueryBus;
use Star\BacklogVelocity\Agile\Application\Query\Project\AllMembersOfTeam;
use Star\BacklogVelocity\Agile\Application\Query\TeamMemberDTO;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class BacklogQueryExtension extends AbstractExtension
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @param QueryBus $queryBus
     */
    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @param string $teamId
     *
     * @return TeamMemberDTO[]
     */
    public function membersOfTeam(string $teamId): array
    {
        $promise = $this->queryBus->dispatch(new AllMembersOfTeam(TeamId::fromString($teamId)));
        $members = [];
        $promise->done(function (array $result) use (&$members) {
            $members = $result;
        });

        return $members;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('membersOfTeam', [$this, 'membersOfTeam']),
        ];
    }
}
