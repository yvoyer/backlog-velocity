<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Tests\Unit;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Setup;
use Star\Component\Sprint\Entity\Factory\BacklogModelTeamFactory;
use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\ManDays;
use Star\Component\Sprint\Model\ProjectAggregate;
use Star\Component\Sprint\Model\ProjectName;
use Star\Component\Sprint\Model\SprintModel;
use Star\Component\Sprint\Model\TeamMemberModel;
use Star\Plugin\Doctrine\DoctrineObjectManagerAdapter;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Star\Component\Sprint\UnitTestCase;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Plugin\Doctrine\Repository\DoctrinePersonRepository
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
        $helperSet = new HelperSet(array(
            'em' => new EntityManagerHelper($em),
        ));

        $createCommand = new CreateCommand();
        $createCommand->setHelperSet($helperSet);
        $createCommand->run(new ArrayInput(array()), new NullOutput());

        $factory = new BacklogModelTeamFactory();
        $project = ProjectAggregate::emptyProject(
            ProjectId::fromString('test-project'), new ProjectName('Project test')
        );
        $em->persist($project);
        $em->flush();

        $em->persist($team = $factory->createTeam('team-name'));
        $em->flush();

        $em->persist($person = $factory->createPerson('person-name'));
        $em->flush();

        $sprint = $project->createSprint(SprintId::fromString('sprint-name'), new \DateTime());
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

    public function test_should_persist_project()
    {
        $project = $this->adapter->getProjectRepository()->getProjectWithIdentity(
            ProjectId::fromString('test-project')
        );
        $this->assertInstanceOf(Project::class, $project);
    }

    public function test_should_persist_team()
    {
        $team = $this->adapter->getTeamRepository()->findOneByName('team-name');

        $this->assertInstanceOfTeam($team);
        $this->assertSame('team-name', $team->getName(), 'Name is not as expected');
    }

    public function test_should_persist_person()
    {
        $person = $this->adapter->getPersonRepository()->findOneById(PersonId::fromString('person-name'));
        $this->assertInstanceOfPerson($person);
        $this->assertSame('person-name', $person->getName());
    }

    public function test_should_persist_sprint()
    {
        $sprint = $this->adapter->getSprintRepository()->findOneById(SprintId::fromString('sprint-name'));
        $this->assertInstanceOfSprint($sprint);
        $this->assertSame('Sprint 1', $sprint->getName());
        $this->assertFalse($sprint->isStarted(), 'Sprint should not be started');
        $this->assertFalse($sprint->isClosed(), 'Sprint should not be closed');
        $this->assertSame(0, $sprint->getEstimatedVelocity());
        $this->assertSame(0, $sprint->getActualVelocity());
        $this->assertSame(0, $sprint->getManDays()->toInt());
        $this->assertCount(0, $sprint->getCommitments());
    }

    /**
     * @depends test_should_persist_sprint
     */
    public function test_it_should_persist_started_sprint() {
        $sprint = $this->adapter->getSprintRepository()->findOneById(SprintId::fromString('sprint-name'));
        $sprint->commit(PersonId::fromString('person-id'), ManDays::fromInt(5));
        $sprint->start(10, new \DateTime());
        $this->adapter->getSprintRepository()->saveSprint($sprint);
        $this->getEntityManager()->clear();

        $sprint = $this->adapter->getSprintRepository()->findOneById(SprintId::fromString('sprint-name'));
        $this->assertInstanceOfSprint($sprint);
        $this->assertTrue($sprint->isStarted(), 'Sprint should be started');
        $this->assertFalse($sprint->isClosed(), 'Sprint should not be closed');
        $this->assertSame(10, $sprint->getEstimatedVelocity());
        $this->assertSame(0, $sprint->getActualVelocity());
        $this->assertSame(5, $sprint->getManDays()->toInt());
        $this->assertCount(1, $sprint->getCommitments());
    }

    /**
     * @depends test_it_should_persist_started_sprint
     */
    public function test_it_should_persist_closed_sprint()
    {
        $sprint = $this->adapter->getSprintRepository()->findOneById(SprintId::fromString('sprint-name'));
        $this->assertTrue($sprint->isStarted(), 'Sprint should be started');
        $sprint->close(30, new \DateTime());
        $this->adapter->getSprintRepository()->saveSprint($sprint);
        $this->getEntityManager()->clear();

        $sprint = $this->adapter->getSprintRepository()->findOneById(SprintId::fromString('sprint-name'));
        $this->assertInstanceOfSprint($sprint);
        $this->assertFalse($sprint->isStarted(), 'Sprint should not be started');
        $this->assertTrue($sprint->isClosed(), 'Sprint should be closed');
        $this->assertSame(10, $sprint->getEstimatedVelocity());
        $this->assertSame(30, $sprint->getActualVelocity());
        $this->assertSame(5, $sprint->getManDays()->toInt());
        $this->assertCount(1, $sprint->getCommitments());
        $this->assertSame(600, $sprint->getFocusFactor()); // todo should be percent
    }

    /**
     * @ticket #48
     *
     * @expectedException        \Doctrine\DBAL\DBALException
     * @expectedExceptionMessage Integrity constraint violation: 19
     */
    public function test_should_not_authorize_duplicate_sprint_name_for_project()
    {
        $sprint = new SprintModel(SprintId::uuid(), 'sprint-name', ProjectId::fromString('test-project'), new \DateTime());
        $this->adapter->getSprintRepository()->saveSprint($sprint);
        $this->adapter->getSprintRepository()->saveSprint(
            new SprintModel(SprintId::uuid(), $sprint->getName(), $sprint->projectId(), new \DateTime())
        );
    }

    /**
     * @ticket #46
     *
     * @expectedException        \Doctrine\DBAL\DBALException
     * @expectedExceptionMessage Integrity constraint violation: 19
     */
    public function test_should_not_authorize_a_person_twice_in_a_team()
    {
        $team = $this->adapter->getTeamRepository()->findOneByName('team-name');
        $person = $this->adapter->getPersonRepository()->findOneById(PersonId::fromString('person-name'));

        $connection = $this->getEntityManager()->getConnection();
        $connection->insert(
            'backlog_team_members',
            [
                'person_id' => $person->getId()->toString(),
                'team_id' => $team->getId()->toString(),
            ]
        );
        $connection->insert(
            'backlog_team_members',
            [
                'person_id' => $person->getId()->toString(),
                'team_id' => $team->getId()->toString(),
            ]
        );
    }

    public function test_it_should_return_ended_sprints_of_project()
    {
        $projectOne = ProjectId::fromString('project-1');
        $projectTwo = ProjectId::fromString('project-2');
        $this->getEntityManager()->beginTransaction();

        $this->assertSprintIsCreated($sprintOne = SprintId::fromString('s1'), $projectOne);
        $this->assertStartedSprintIsCreated($sprintTwo = SprintId::fromString('s2'), $projectOne);
        $this->assertEndedSprintIsCreated($sprintThree = SprintId::fromString('s3'), $projectOne);

        $this->assertSprintIsCreated($sprintFour = SprintId::fromString('s4'), $projectTwo);
        $this->assertStartedSprintIsCreated($sprintFive = SprintId::fromString('s5'), $projectTwo);
        $this->assertEndedSprintIsCreated($sprintSix = SprintId::fromString('s6'), $projectTwo);

        $this->getEntityManager()->clear();

        $sprints = $this->adapter->getSprintRepository();
        $this->assertEquals(
            [
                $sprintSix
            ],
            array_map(
                function (Sprint $sprint) {
                    return $sprint->getId();
                },
                $sprints->endedSprints($projectTwo)
            )
        );

        $this->getEntityManager()->rollback();
    }

    /**
     * @param SprintId $sprintId
     * @param ProjectId $projectId
     *
     * @return SprintModel
     */
    private function assertSprintIsCreated(SprintId $sprintId, ProjectId $projectId)
    {
        $sprints = $this->adapter->getSprintRepository();
        $sprint = new SprintModel($sprintId, uniqid(), $projectId, new \DateTime());

        $sprints->saveSprint($sprint);

        return $sprint;
    }

    /**
     * @param SprintId $sprintId
     * @param ProjectId $projectId
     *
     * @return SprintModel
     */
    private function assertStartedSprintIsCreated(SprintId $sprintId, ProjectId $projectId)
    {
        $sprints = $this->adapter->getSprintRepository();
        $sprint = $this->assertSprintIsCreated($sprintId, $projectId);
        $sprint->commit(PersonId::fromString('person-name'), ManDays::fromInt(3));
        $sprint->start(mt_rand(), new \DateTime());
        $sprints->saveSprint($sprint);

        return $sprint;
    }

    /**
     * @param SprintId $sprintId
     * @param ProjectId $projectId
     *
     * @return SprintModel
     */
    private function assertEndedSprintIsCreated(SprintId $sprintId, ProjectId $projectId)
    {
        $sprints = $this->adapter->getSprintRepository();
        $sprint = $this->assertStartedSprintIsCreated($sprintId, $projectId);
        $sprint->close(mt_rand(), new \DateTime());
        $sprints->saveSprint($sprint);

        return $sprint;
    }
}
