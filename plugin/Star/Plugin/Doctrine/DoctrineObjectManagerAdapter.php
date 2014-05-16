<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Star\Component\Sprint\Entity\Repository\MemberRepository;
use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\SprintMemberData;
use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Mapping\TeamMemberData;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\SprintModel;
use Star\Component\Sprint\Model\TeamMemberModel;
use Star\Component\Sprint\Model\TeamModel;
use Star\Component\Sprint\Repository\RepositoryManager;
use Star\Plugin\Doctrine\Repository\DoctrinePersonRepository;
use Star\Plugin\Doctrine\Repository\DoctrineSprinterRepository;
use Star\Plugin\Doctrine\Repository\DoctrineSprintMemberRepository;
use Star\Plugin\Doctrine\Repository\DoctrineSprintRepository;
use Star\Plugin\Doctrine\Repository\DoctrineTeamMemberRepository;
use Star\Plugin\Doctrine\Repository\DoctrineTeamRepository;

/**
 * Class DoctrineObjectManagerAdapter
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine
 */
class DoctrineObjectManagerAdapter implements RepositoryManager
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Returns the Team repository.
     *
     * @return TeamRepository
     */
    public function getTeamRepository()
    {
        return new DoctrineTeamRepository(TeamModel::CLASS_NAME, $this->objectManager);
    }

    /**
     * Returns the Team repository.
     *
     * @return SprintRepository
     */
    public function getSprintRepository()
    {
        return new DoctrineSprintRepository(SprintModel::CLASS_NAME, $this->objectManager);
    }

    /**
     * Returns the Team repository.
     *
     * @return SprinterRepository
     */
    public function getSprinterRepository()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
//        return new DoctrineSprinterRepository(SprinterData::LONG_NAME, $this->objectManager);
    }

    /**
     * Returns the Team repository.
     *
     * @return SprintMemberRepository
     */
    public function getSprintMemberRepository()
    {
        return new DoctrineSprintMemberRepository(SprintMemberData::LONG_NAME, $this->objectManager);
    }

    /**
     * Returns the Team repository.
     *
     * @return TeamMemberRepository
     */
    public function getTeamMemberRepository()
    {
        return new DoctrineTeamMemberRepository(TeamMemberModel::CLASS_NAME, $this->objectManager);
    }

    /**
     * @return MemberRepository
     */
    public function getPersonRepository()
    {
        return new DoctrinePersonRepository(PersonModel::CLASS_NAME, $this->objectManager);
    }
}
