<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Tests\Unit;

use Doctrine\Common\Util\Debug;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Setup;
use Star\Component\Sprint\Entity\Factory\BacklogModelTeamFactory;
use Star\Component\Sprint\Model\Identity\SprintId;
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
 *
 * @covers Star\Plugin\Doctrine\Repository\DoctrinePersonRepository
 * @covers Star\Plugin\Doctrine\Repository\DoctrineSprintMemberRepository
 * @covers Star\Plugin\Doctrine\Repository\DoctrineSprintRepository
 * @covers Star\Plugin\Doctrine\Repository\DoctrineTeamMemberRepository
 * @covers Star\Plugin\Doctrine\Repository\DoctrineTeamRepository
 *
 * @uses Star\Plugin\Doctrine\DoctrineObjectManagerAdapter
 * @uses Star\Plugin\Doctrine\Repository\DoctrineRepository
 * @uses Star\Component\Sprint\Model\PersonModel
 * @uses Star\Component\Sprint\Model\TeamModel
 * @uses Star\Component\Sprint\Model\TeamMemberModel
 * @uses Star\Component\Sprint\Model\SprintModel
 * @uses Star\Component\Sprint\Model\SprintMemberModel
 * @uses Star\Component\Sprint\Model\Identity\PersonId
 * @uses Star\Component\Sprint\Model\Identity\SprintId
 * @uses Star\Component\Sprint\Type\String
 */
class DoctrineMappingTest extends UnitTestCase
{
    /**
     * @var Connection
     */
    private static $connection;

    /**
     * @var DoctrineObjectManagerAdapter
     */
    private $adapter;

    public static function setUpBeforeClass()
    {
        $em = self::getEntityManager();
        $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
            'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em),
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
        $sprint->start(123);
        $sprint->close(456);

        $em->persist($team);
        $em->persist($person);
        $em->persist($teamMember);
        $em->persist($sprintMember);
        $em->persist($sprint);
        $em->flush();
        $em->clear();
    }

    /**
     * @return EntityManager
     */
    private static function getEntityManager()
    {
        if (null === self::$connection) {
            self::$connection = DriverManager::getConnection(array(
                    'driver' => 'pdo_sqlite',
                    'memory' => true,
                )
            );
        }

        $root = dirname(dirname(__DIR__));
        $config = Setup::createXMLMetadataConfiguration(array($root . '/Resources/config/doctrine'), true);
        // $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . '/Entity'), true);

        return EntityManager::create(self::$connection, $config);
    }

    public function setUp()
    {
        $this->adapter = new DoctrineObjectManagerAdapter(self::getEntityManager());
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
        $this->assertSame(123, $sprint->getEstimatedVelocity());
        $this->assertSame(456, $sprint->getActualVelocity());
        $this->assertTrue($sprint->isClosed(), 'Sprint should be closed');
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

    /**
     * @ticket #48
     *
     * @depends test_should_persist_sprint
     *
     * @expectedException        \Doctrine\DBAL\DBALException
     * @expectedExceptionMessage Integrity constraint violation: 19
     */
    public function test_should_not_authorize_duplicate_sprint_name_for_team()
    {
        $team = $this->adapter->getTeamRepository()->findOneByName('team-name');
        $this->assertInstanceOfTeam($team);
        $sprint = $this->adapter->getSprintRepository()->findOneByName('sprint-name');
        $this->assertInstanceOfSprint($sprint);

        $newSprint = new SprintModel(SprintId::uuid(), 'sprint-name', $team);
        $this->adapter->getSprintRepository()->add($newSprint);
        $this->adapter->getSprintRepository()->save();
    }

    /**
     * @ticket #46
     *
     * @depends test_should_persist_team_member
     *
     * @expectedException        \Doctrine\DBAL\DBALException
     * @expectedExceptionMessage Integrity constraint violation: 19
     */
    public function test_should_not_authorize_duplicate_team_member_on_team()
    {
        $teamMember = $this->adapter->getTeamMemberRepository()->findMemberOfSprint('person-name', 'sprint-name');
        $newTeamMember = new TeamMemberModel($teamMember->getTeam(), $teamMember->getPerson());

        $this->adapter->getTeamMemberRepository()->add($newTeamMember);
        $this->adapter->getTeamMemberRepository()->save();
    }
}
