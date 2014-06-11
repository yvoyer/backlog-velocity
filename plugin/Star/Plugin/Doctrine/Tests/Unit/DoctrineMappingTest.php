<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Tests\Unit;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Setup;
use Star\Component\Sprint\Entity\Factory\BacklogModelTeamFactory;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\SprintMemberModel;
use Star\Component\Sprint\Model\SprintModel;
use Star\Component\Sprint\Model\TeamMemberModel;
use Star\Component\Sprint\Model\TeamModel;
use Star\Plugin\Doctrine\DoctrineObjectManagerAdapter;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use tests\UnitTestCase;

/**
 * Class DoctrineMappingTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit
 */
class DoctrineMappingTest extends UnitTestCase
{
    /**
     * @var EntityManager
     */
    private static $entityManager;

    /**
     * @var DoctrineObjectManagerAdapter
     */
    private $adapter;

    public static function setUpBeforeClass()
    {
        $root = dirname(dirname(__DIR__));
        $config = Setup::createXMLMetadataConfiguration(array($root . '/Resources/config/doctrine'), true);
        // $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . '/Entity'), true);

        $connection = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        self::$entityManager = EntityManager::create($connection, $config);
        $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
            'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper(self::$entityManager),
        ));

        $createCommand = new CreateCommand();
        $createCommand->setHelperSet($helperSet);
        $createCommand->run(new ArrayInput(array()), new NullOutput());

        $factory = new BacklogModelTeamFactory();
        $team = $factory->createTeam('team-name');
        $person = $factory->createPerson('person-name');
        $teamMember = $team->addTeamMember($person);
        $sprint = $team->createSprint('sprint-name');
        $sprintMember = $sprint->commit($teamMember, 234);

        self::$entityManager->persist($team);
        self::$entityManager->persist($person);
        self::$entityManager->persist($teamMember);
        self::$entityManager->persist($sprintMember);
        self::$entityManager->persist($sprint);
        self::$entityManager->flush();
        self::$entityManager->clear();
    }

    public function setUp()
    {
        $this->adapter = new DoctrineObjectManagerAdapter(self::$entityManager);
    }

    public function test_should_persist_team()
    {
        $team = $this->adapter->getTeamRepository()->findOneByName('team-name');

        $this->assertInstanceOfTeam($team);
        $this->assertSame('team-name', $team->getName(), 'Name is not as expected');
        $this->assertContainsOnlyInstancesOf(TeamMemberModel::CLASS_NAME, $team->getTeamMembers());
        $this->assertContainsOnlyInstancesOf(SprintModel::CLASS_NAME, $team->getClosedSprints());
    }

    public function test_should_persist_person()
    {
        $person = $this->adapter->getPersonRepository()->findOneByName('person-name');
        $this->assertInstanceOfPerson($person);
        $this->assertSame('person-name', $person->getName());
    }

    public function test_should_persist_sprint()
    {
        $sprint = $this->adapter->getSprintRepository()->findOneByName('sprint-name');
        $this->assertInstanceOfSprint($sprint);
        $this->assertSame('sprint-name', $sprint->getName());
        $this->assertInstanceOf(TeamModel::CLASS_NAME, $sprint->getTeam());
        $this->assertAttributeContainsOnly(SprintMemberModel::LONG_NAME, 'sprintMembers', $sprint);
    }

    public function test_should_persist_team_member()
    {
        $teamMember = $this->adapter->getTeamMemberRepository()->findMemberOfSprint('person-name', 'sprint-name');
        $this->assertInstanceOf(TeamMemberModel::CLASS_NAME, $teamMember);
        $this->assertInstanceOf(TeamModel::CLASS_NAME, $teamMember->getTeam());
        $this->assertInstanceOf(PersonModel::CLASS_NAME, $teamMember->getPerson());
    }

    public function test_should_persist_sprint_member()
    {
        /**
         * @var $sprintMember SprintMemberModel
         */
        $sprintMember = $this->adapter->getSprintMemberRepository()->find(1);

        $this->assertInstanceOf(SprintMemberModel::LONG_NAME, $sprintMember);
        $this->assertInstanceOf(SprintModel::CLASS_NAME, $sprintMember->getSprint());
        $this->assertInstanceOf(TeamMemberModel::CLASS_NAME, $sprintMember->getTeamMember());
        $this->assertSame(234, $sprintMember->getAvailableManDays());
    }
}
