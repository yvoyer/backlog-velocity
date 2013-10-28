<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Functional;

use Doctrine\ORM\Tools\Setup;
use Star\Component\Sprint\BacklogApplication;
use Star\Component\Sprint\Entity\Factory\DefaultObjectFactory;
use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\Null\NullTeam;
use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Mapping\Entity;
use Star\Component\Sprint\Mapping\Repository\DefaultMapping;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Null\NullDialog;
use Star\Component\Sprint\Repository\Doctrine\DoctrineObjectManagerAdapter;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
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

    /**
     * @var EntityCreator
     */
    private $creator;

    /**
     * @var \Doctrine\ORM\Configuration
     */
    private static $config;

    /**
     * @var array
     */
    private static $connection;

    public static function setUpBeforeClass()
    {
        $isDevMode = true;
        // $entityFolder = __DIR__ . '/Entity';
        // $config = Setup::createAnnotationMetadataConfiguration(array($entityFolder), $isDevMode);
        $root = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
        self::$config = Setup::createXMLMetadataConfiguration(array($root . '/config/doctrine'), $isDevMode);

        self::$connection = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );
    }

    public function setUp()
    {
        $this->creator     = new DefaultObjectFactory();
        $this->application = $this->getApplication();
    }

    /**
     * Create a sprint.
     *
     * @param string $name
     *
     * @return SprintData
     */
    protected function generateSprint($name)
    {
        $sprint = $this->creator->createSprint($name, new NullTeam(), 0);

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
    protected function generateSprinter($name)
    {
        $sprinter = $this->creator->createSprinter($name);

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
    protected function generateTeam($name)
    {
        $team = $this->creator->createTeam($name);

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
    protected function generateTeamMember(Sprinter $member, Team $team)
    {
        $teamMember = $this->creator->createTeamMember($member, $team);

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
     * @param Sprint     $sprint
     * @param TeamMember $teamMember
     *
     * @return SprintMember
     */
    protected function generateSprintMember($availableManDays, $actualVelocity, Sprint $sprint, TeamMember $teamMember)
    {
        $sprintMember = $this->creator->createSprintMember($availableManDays, $actualVelocity, $sprint, $teamMember);

        $em = $this->getEntityManager();
        $em->persist($sprintMember);
        $em->flush();

        return $sprintMember;
    }

    /**
     * @param DialogHelper    $dialogHelper
     * @param OutputInterface $output
     *
     * @return BacklogApplication
     */
    protected function getApplication(DialogHelper $dialogHelper = null, OutputInterface $output = null)
    {
        if (null === $dialogHelper) {
            $dialogHelper = new NullDialog();
        }

        if (null === $output) {
            $output = new NullOutput();
        }

        $this->application = new BacklogApplication(self::$connection, self::$config, $dialogHelper, $output);
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
        return $this->getRepositoryManager()->getSprintRepository();
    }

    /**
     * @return SprinterRepository
     */
    protected function getSprinterRepository()
    {
        return $this->getRepositoryManager()->getSprinterRepository();
    }

    /**
     * @return TeamRepository
     */
    protected function getTeamRepository()
    {
        return $this->getRepositoryManager()->getTeamRepository();
    }

    /**
     * @return SprintMemberRepository
     */
    protected function getSprintMemberRepository()
    {
        return $this->getgetRepositoryManager()->getSprintMemberRepository();
    }

    /**
     * @return DoctrineObjectManagerAdapter
     */
    private function getRepositoryManager()
    {
        return new DoctrineObjectManagerAdapter($this->getEntityManager(), new DefaultMapping());
    }
}
