<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Functional;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;
use Star\Component\Sprint\BacklogApplication;
use Star\Component\Sprint\Entity\Factory\DefaultObjectFactory;
use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\Null\NullTeam;
use Star\Component\Sprint\Entity\ObjectManager;
use Star\Component\Sprint\Entity\Query\DoctrineObjectFinder;
use Star\Component\Sprint\Entity\Query\EntityFinder;
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
use Star\Component\Sprint\Repository\RepositoryManager;
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
     * @var EntityCreator
     */
    private static $creator;

    /**
     * @var EntityFinder
     */
    private static $finder;

    /**
     * @var \Doctrine\ORM\Configuration
     */
    private static $config;

    /**
     * @var array
     */
    private static $connection;

    /**
     * @var RepositoryManager
     */
    private static $repositoryManager;

    /**
     * @var ObjectManager
     */
    private static $objectManager;

    /**
     * @var BacklogApplication
     */
    private static $console;

    /**
     * @var EntityManager
     */
    private static $entityManager;

    public static function setUpBeforeClass()
    {
        throw new \PHPUnit_Framework_IncompleteTestError();
        // $entityFolder = __DIR__ . '/Entity';
        // $config = Setup::createAnnotationMetadataConfiguration(array($entityFolder), $isDevMode);
        $root = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
        self::$config = Setup::createXMLMetadataConfiguration(array($root . '/config/doctrine'), true);

        self::$connection = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        self::$entityManager = EntityManager::create(self::$connection, self::$config);
        $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
            'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper(self::$entityManager->getConnection()),
            'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper(self::$entityManager),
        ));

        $mapping                 = new DefaultMapping();
        self::$repositoryManager = new DoctrineObjectManagerAdapter(self::$entityManager, $mapping);
        self::$finder            = new DoctrineObjectFinder(self::$repositoryManager);
        self::$creator           = new DefaultObjectFactory();
        self::$objectManager     = new ObjectManager(self::$creator, self::$finder);

        self::$console = new BacklogApplication(self::$repositoryManager, self::$objectManager, self::$creator, self::$finder);
        self::$console->setCatchExceptions(false);
        self::$console->setAutoExit(false);
        self::$console->setHelperSet($helperSet);
        ConsoleRunner::addCommands(self::$console);
    }

    /**
     * @return BacklogApplication
     */
    protected function getApplication()
    {
        $tester = $this->getApplicationTester(self::$console);
        // Automatic schema creation
        $tester->run(array('o:s:c'));

        return self::$console;
    }

    public function setUp()
    {
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
        $sprint = self::$creator->createSprint($name, new NullTeam(), 0);
        $this->generateEntity($sprint);

        return $sprint;
    }

    /**
     * @param string $name
     *
     * @return Sprinter
     */
    protected function generateSprinter($name)
    {
        $sprinter = self::$creator->createSprinter($name);
        $this->generateEntity($sprinter);

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
        $team = self::$creator->createTeam($name);
        $this->generateEntity($team);

        return $team;
    }

    /**
     * Create a team member.
     *
     * @param Sprinter $member
     * @param Team     $team
     * @param integer  $availableManDays
     *
     * @return TeamMember
     */
    protected function generateTeamMember(Sprinter $member, Team $team, $availableManDays)
    {
        $teamMember = self::$creator->createTeamMember($member, $team, $availableManDays);
        $this->generateEntity($teamMember);

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
        $sprintMember = self::$creator->createSprintMember($availableManDays, $actualVelocity, $sprint, $teamMember);
        $this->generateEntity($sprintMember);

        return $sprintMember;
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
        return self::$entityManager;
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
        return $this->getRepositoryManager()->getSprintMemberRepository();
    }

    /**
     * @return DoctrineObjectManagerAdapter
     */
    private function getRepositoryManager()
    {
        return self::$repositoryManager;
    }

    /**
     * @param Entity $entity
     */
    private function generateEntity(Entity $entity)
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
        $em->refresh($entity);
    }
}
