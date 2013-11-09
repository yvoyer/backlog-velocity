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
use Star\Component\Sprint\Entity\Factory\DefaultObjectFactory;
use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\Null\NullTeam;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Mapping\Entity;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprintMemberData;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * Class DoctrineMappingTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Functional
 */
class DoctrineMappingTest extends UnitTestCase
{
    /**
     * @var EntityManager
     */
    private static $entityManager;

    /**
     * @var EntityCreator
     */
    private $creator;

    public static function setUpBeforeClass()
    {
        // $entityFolder = __DIR__ . '/Entity';
        // $config = Setup::createAnnotationMetadataConfiguration(array($entityFolder), $isDevMode);
        $root = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
        $config = Setup::createXMLMetadataConfiguration(array($root . '/config/doctrine'), true);

        $connection = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        self::$entityManager = EntityManager::create($connection, $config);
        $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
            'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper(self::$entityManager->getConnection()),
            'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper(self::$entityManager),
        ));

        $cli = new Application('test', 'test');
        $cli->setCatchExceptions(true);
        $cli->setAutoExit(false);
        $cli->setHelperSet($helperSet);
        ConsoleRunner::addCommands($cli);

        // Automatic schema creation
        $tester = new ApplicationTester($cli);
        $tester->run(array('o:s:c'));
    }

    public function setUp()
    {
        $this->creator = new DefaultObjectFactory();
    }

    public function testShouldPersistTeam()
    {
        $name = uniqid('team');
        $team = $this->generateTeam($name);

        /**
         * @var $team Team
         */
        $team = $this->getRefreshedObject($team);

        $this->assertSame($name, $team->getName(), 'Name is not as expected');
    }

    public function testShouldPersistSprinter()
    {
        $name     = uniqid('sprinter-name-');
        $sprinter = $this->generateSprinter($name);

        /**
         * @var $sprinter Sprinter
         */
        $sprinter = $this->getRefreshedObject($sprinter);

        $this->assertSame($name, $sprinter->getName(), 'Name is not as expected');
    }

    /**
     * @depends testShouldPersistTeam
     * @depends testShouldPersistSprinter
     */
    public function testShouldPersistTeamMember()
    {
        $team       = $this->generateTeam(uniqid('team'));
        $sprinter   = $this->generateSprinter(uniqid('sprinter'));
        $teamMember = $team->addMember($sprinter, 5);

        $em = $this->getEntityManager();
        $em->persist($teamMember);
        $em->flush();

        /**
         * @var $teamMember TeamMember
         */
        $teamMember = $this->getRefreshedObject($teamMember);
        $this->assertSame(5, $teamMember->getAvailableManDays());
        $this->assertInstanceOfSprinter($teamMember->getMember());
        $this->assertInstanceOfTeam($teamMember->getTeam());

        /**
         * @var $team Team
         */
        $team = $this->getRefreshedObject($team);
        $this->assertCount(1, $team->getMembers());
    }

    /**
     * @depends testShouldPersistTeam
     */
    public function testShouldPersistSprint()
    {
        $team       = $this->generateTeam(uniqid('team'));
        $repository = $this->getEntityManager()->getRepository(SprintData::LONG_NAME);
        $name       = uniqid('sprint');

        $this->assertEmpty($repository->findAll(), 'Sprint list should be empty');
        $sprint = $this->generateSprint($name, $team);
        $this->getRefreshedObject($sprint);
        $this->assertCount(1, $repository->findAll(), 'Sprint list should contain 1 element');

        $this->assertSame($name, $sprint->getName());
    }

    /**
     * @depends testShouldPersistSprinter
     * @depends testShouldPersistTeam
     * @depends testShouldPersistSprint
     */
    public function testShouldPersistSprintMember()
    {
        $availableManDays = 100;
        $actualVelocity   = 200;
        $sprint           = $this->generateSprint(uniqid('sprint'));
        $team             = $this->generateTeam(uniqid('team'));
        $sprinter         = $this->generateSprinter(uniqid('sprinter'));
        $teamMember       = $this->generateTeamMember($sprinter, $team, $availableManDays);
        $repository       = $this->getEntityManager()->getRepository(SprintMemberData::LONG_NAME);

        $this->assertEmpty($repository->findAll());
        $sprintMember = $this->generateSprintMember($availableManDays, $actualVelocity, $sprint, $teamMember);
        $this->assertCount(1, $repository->findAll());

        $this->getRefreshedObject($sprintMember);
        $this->assertSame($sprint, $sprintMember->getSprint());
        $this->assertSame($teamMember, $sprintMember->getTeamMember());

        $teamMember = $sprintMember->getTeamMember();
        $this->assertSame($availableManDays, $teamMember->getAvailableManDays());
        $this->assertSame($availableManDays, $sprintMember->getAvailableManDays());
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
        $sprinter = $this->creator->createSprinter($name);
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
        $team = $this->creator->createTeam($name);
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
        $teamMember = $this->creator->createTeamMember($member, $team, $availableManDays);
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
        $sprintMember = $this->creator->createSprintMember($availableManDays, $actualVelocity, $sprint, $teamMember);
        $this->generateEntity($sprintMember);

        return $sprintMember;
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
}
