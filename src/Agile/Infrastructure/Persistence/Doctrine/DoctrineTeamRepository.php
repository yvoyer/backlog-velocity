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
    /**
     * Find the object based on name.
     *
     * @param string $name
     *
     * @return Team
     * @throws EntityNotFoundException
     */
    public function findOneByName(string $name) :Team
    {
        $team = $this->findOneBy(array('name' => $name));
        if (! $team) {
            throw EntityNotFoundException::objectWithAttribute(Team::class, 'name', $name);
        }

        return $team;
    }

    /**
     * @param TeamId $teamId
     * @return Team
     * @throws EntityNotFoundException
     */
    public function getTeamWithIdentity(TeamId $teamId): Team
    {
        $qb = $this->createQueryBuilder('team');
        $qb->andWhere($qb->expr()->eq('team.id', ':team_id'));
        $qb->setParameter('team_id', $teamId->toString());
        $team = $qb->getQuery()->getOneOrNullResult();
        if (! $team) {
            throw EntityNotFoundException::objectWithIdentity($teamId);
        }

        return $team;
    }

    /**
     * @param TeamName $name
     *
     * @return bool
     */
    public function teamWithNameExists(TeamName $name) :bool
    {
        return (bool) $this->findOneBy(array('name' => $name->toString()));
    }

    /**
     * @return Team[]
     */
    public function allTeams() :array
    {
        return $this->findAll();
    }

    /**
     * @param Team $team
     */
    public function saveTeam(Team $team)
    {
        $this->_em->persist($team);
        $this->_em->flush();
    }

    /**
     * @param TeamId $teamId
     *
     * @return bool
     */
    public function teamWithIdentityExists(TeamId $teamId): bool
    {
        return (bool) $this->findOneBy(array('id' => $teamId->toString()));
    }
}
