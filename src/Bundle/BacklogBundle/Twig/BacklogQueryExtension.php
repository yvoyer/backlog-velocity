<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Twig;

use Prooph\ServiceBus\QueryBus;
use Star\BacklogVelocity\Agile\Application\Query\Project\AllMembersOfTeam;
use Star\BacklogVelocity\Agile\Application\Query\SprintDTO;
use Star\BacklogVelocity\Agile\Application\Query\TeamMemberDTO;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Twig\TwigFunction;

final class BacklogQueryExtension extends \Twig_Extension
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
    public function membersOfTeam(string $teamId) :array
    {
        $promise = $this->queryBus->dispatch(new AllMembersOfTeam(TeamId::fromString($teamId)));
        $members = [];
        $promise->done(function (array $result) use (&$members) {
            $members = $result;
        });

        return $members;
    }

    public function createdAt(SprintDTO $sprint) :\DateTimeInterface
    {
        $value = null;
        $this->queryBus
            ->dispatch(new CreatedDateOfSprint(SprintId::fromString($sprint->id)))
            ->done(function (\DateTimeInterface $date) use (&$value) {
                $value = $date;
            });

        return $value;
    }

    public function startedAt(SprintDTO $sprint) :\DateTimeInterface
    {
        if (! $sprint->isStarted()) {
            throw new \RuntimeException('Sprint is not started, cannot get the started at date.');
        }

        $value = null;
        $this->queryBus
            ->dispatch(new StartedDateOfSprint(SprintId::fromString($sprint->id)))
            ->done(function (\DateTimeInterface $date) use (&$value) {
                $value = $date;
            });

        return $value;
    }

    public function endedAt(SprintDTO $sprint) :\DateTimeInterface
    {
        if (! $sprint->isClosed()) {
            throw new \RuntimeException('Sprint is not closed, cannot get the closed at date.');
        }

        $value = null;
        $this->queryBus
            ->dispatch(new EndedDateOfSprint(SprintId::fromString($sprint->id)))
            ->done(function (\DateTimeInterface $date) use (&$value) {
                $value = $date;
            });

        return $value;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('membersOfTeam', [$this, 'membersOfTeam']),
            new TwigFunction('createdAt', [$this, 'createdAt']),
            new TwigFunction('startedAt', [$this, 'startedAt']),
            new TwigFunction('endedAt', [$this, 'endedAt']),
        ];
    }
}
