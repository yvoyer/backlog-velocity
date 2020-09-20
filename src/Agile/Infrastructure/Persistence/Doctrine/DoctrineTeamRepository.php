<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityRepository;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\Team;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamName;
use Star\BacklogVelocity\Agile\Domain\Model\TeamRepository;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class DoctrineTeamRepository extends EntityRepository implements TeamRepository
{
    public function findOneByName(string $name): Team
    {
        $team = $this->findOneBy(array('name' => $name));
        if (! $team instanceof Team) {
            throw EntityNotFoundException::objectWithAttribute(Team::class, 'name', $name);
        }

        return $team;
    }

    public function getTeamWithIdentity(TeamId $teamId): Team
    {
        $qb = $this->createQueryBuilder('team');
        $qb->andWhere($qb->expr()->eq('team.id', ':team_id'));
        $qb->setParameter('team_id', $teamId->toString());
        $team = $qb->getQuery()->getOneOrNullResult();
        if (! $team instanceof Team) {
            throw EntityNotFoundException::objectWithIdentity($teamId);
        }

        return $team;
    }

    public function teamWithNameExists(TeamName $name): bool
    {
        return (bool) $this->findOneBy(array('name' => $name->toString()));
    }

    /**
     * @return Team[]
     */
    public function allTeams(): array
    {
        return $this->findAll();
    }

    public function saveTeam(Team $team): void
    {
        $this->_em->persist($team);
        $this->_em->flush();
    }

    public function teamWithIdentityExists(TeamId $teamId): bool
    {
        return (bool) $this->findOneBy(array('id' => $teamId->toString()));
    }
}
