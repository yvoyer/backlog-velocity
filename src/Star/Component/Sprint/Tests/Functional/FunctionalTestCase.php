<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Functional;

use Doctrine\ORM\Tools\Setup;
use Star\Component\Sprint\BacklogApplication;
use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Mapping\Entity;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\SprintMemberData;
use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Mapping\TeamMemberData;
use Star\Component\Sprint\Null\NullDialog;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * Class FunctionalTestCase
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Functional
 */
class FunctionalTestCase extends UnitTestCase
{
    /**
     * @var BacklogApplication
     */
    private $application;

    public function setUp()
    {
        $this->setupApplication();
    }

    /**
     * Create a sprint.
     *
     * @param string $name
     *
     * @return SprintData
     */
    protected function createSprint($name)
    {
        // @todo Add dep to team
        $sprint = new SprintData($name, $this->createTeam(''));

        $em = $this->getEntityManager();
        $em->persist($sprint);
        $em->flush();
        $em->refresh($sprint);

        return $sprint;
    }

    /**
     * @param string $name
     *
     * @return Sprinter
     */
    protected function createSprinter($name)
    {
        $sprinter = new SprinterData($name);

        $em = $this->getEntityManager();
        $em->persist($sprinter);
        $em->flush();
        $em->refresh($sprinter);

        return $sprinter;
    }

    /**
     * Creates a Team.
     *
     * @param string $name
     *
     * @return Team
     */
    protected function createTeam($name)
    {
        $team = new TeamData($name);

        $em = $this->getEntityManager();
        $em->persist($team);
        $em->flush();

        return $team;
    }

    /**
     * Create a team member.
     *
     * @param Sprinter $member
     * @param Team     $team
     *
     * @return TeamMember
     */
    protected function createTeamMember(Sprinter $member, Team $team)
    {
        $teamMember = new TeamMemberData($member, $team);

        $em = $this->getEntityManager();
        $em->persist($teamMember);
        $em->flush();

        return $teamMember;
    }

    /**
     * Create Sprint member.
     *
     * @param integer    $availableManDays
     * @param integer    $actualVelocity
     * @param SprintData $sprint
     * @param TeamMember $teamMember
     *
     * @return SprintMember
     */
    protected function createSprintMember($availableManDays, $actualVelocity, SprintData $sprint, TeamMember $teamMember)
    {
        $sprintMember = new SprintMemberData($availableManDays, $actualVelocity, $sprint, $teamMember);

        $em = $this->getEntityManager();
        $em->persist($sprintMember);
        $em->flush();

        return $sprintMember;
    }

    /**
     * @param DialogHelper $dialogHelper
     *
     * @return BacklogApplication
     */
    protected function setupApplication(DialogHelper $dialogHelper = null)
    {
        if (null === $dialogHelper) {
            $dialogHelper = new NullDialog();
        }

        $isDevMode = true;
        // $entityFolder = __DIR__ . '/Entity';
        // $config = Setup::createAnnotationMetadataConfiguration(array($entityFolder), $isDevMode);
        $root = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
        $config = Setup::createXMLMetadataConfiguration(array($root . '/config/doctrine'), $isDevMode);

        $conn = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        $this->application = new BacklogApplication($conn, $config, $dialogHelper);
        $this->application->setAutoExit(false);

        $tester = $this->getApplicationTester($this->application);
        // Automatic schema creation
        $tester->run(array('o:s:c'));

        return $this->application;
    }

    /**
     * @param Application $application
     *
     * @return ApplicationTester
     */
    protected function getApplicationTester(Application $application)
    {
        return new ApplicationTester($application);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->application->getEntityManager();
    }

    /**
     * Returns a refreshed object containing data from db.
     *
     * @param Entity $object
     *
     * @return Entity
     */
    protected function getRefreshedObject(Entity $object)
    {
        $em = $this->getEntityManager();
        $em->clear();

        $id = $object->getId();
        $this->assertNotNull($id, 'The id should not be null');

        return $em->find(get_class($object), $id);
    }

    /**
     * @return SprintRepository
     */
    protected function getSprintRepository()
    {
        return $this->getEntityManager()->getRepository(SprintData::LONG_NAME);
    }

    /**
     * @return SprinterRepository
     */
    protected function getSprinterRepository()
    {
        return $this->getEntityManager()->getRepository(SprinterData::LONG_NAME);
    }

    /**
     * @return TeamRepository
     */
    protected function getTeamRepository()
    {
        return $this->getEntityManager()->getRepository(TeamData::LONG_NAME);
    }

    /**
     * @return SprintMemberRepository
     */
    protected function getSprintMemberRepository()
    {
        return $this->getEntityManager()->getRepository(SprintMemberData::LONG_NAME);
    }
}
