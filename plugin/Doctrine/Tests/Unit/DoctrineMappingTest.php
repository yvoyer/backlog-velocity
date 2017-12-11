<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Unit;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Entity\Factory\BacklogModelTeamFactory;
use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Entity\Repository\Filters\AllObjects;
use Star\Component\Sprint\Domain\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\ManDays;
use Star\Component\Sprint\Domain\Model\PersonName;
use Star\Component\Sprint\Domain\Model\ProjectAggregate;
use Star\Component\Sprint\Domain\Model\ProjectName;
use Star\Component\Sprint\Domain\Model\SprintModel;
use Star\Component\Sprint\Domain\Model\SprintName;
use Star\Component\Sprint\Domain\Model\TeamName;
use Star\Plugin\Doctrine\DoctrineObjectManagerAdapter;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class DoctrineMappingTest extends TestCase
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

        $em->persist($team = $project->createTeam(TeamId::fromString('t1'), new TeamName('team-name')));
        $em->flush();

        $em->persist($person = $factory->createPerson('person-name'));
        $em->flush();

        $sprint = $project->createSprint(
            SprintId::uuid(), new SprintName('sprint-name'), $team->getId(), new \DateTime()
        );
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
        $this->getEntityManager()->beginTransaction();
        $this->adapter = new DoctrineObjectManagerAdapter(self::getEntityManager());
    }

    public function tearDown()
    {
        $this->getEntityManager()->rollback();
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

        $this->assertInstanceOf(Team::class, $team);
        $this->assertSame('team-name', $team->getName()->toString(), 'Name is not as expected');
    }

    public function test_should_persist_person()
    {
        $person = $this->adapter->getPersonRepository()->personWithName(new PersonName('person-name'));
        $this->assertInstanceOf(Person::class, $person);
        $this->assertSame('person-name', $person->getName()->toString());
    }

    public function test_should_persist_sprint()
    {
        $sprint = $this->adapter->getSprintRepository()->sprintWithName(
            ProjectId::fromString('test-project'), new SprintName('sprint-name')
        );
        $this->assertInstanceOf(Sprint::class, $sprint);
        $this->assertSame('sprint-name', $sprint->getName()->toString());
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
        $sprint = $this->adapter->getSprintRepository()->sprintWithName(
            $projectId = ProjectId::fromString('test-project'),
            $sprintName = new SprintName('sprint-name')
        );
        $sprint->commit(MemberId::fromString('person-id'), ManDays::fromInt(5));
        $sprint->start(10, new \DateTime());
        $this->adapter->getSprintRepository()->saveSprint($sprint);
        $this->getEntityManager()->clear();

        $sprint = $this->adapter->getSprintRepository()->sprintWithName($projectId, $sprintName);
        $this->assertInstanceOf(Sprint::class, $sprint);
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
        $sprint = $this->adapter->getSprintRepository()->sprintWithName(
            $projectId = ProjectId::fromString('test-project'),
            $sprintName = new SprintName('sprint-name')
        );
        $sprint->commit(MemberId::fromString('person-id'), ManDays::fromInt(5));
        $sprint->start(10, new \DateTime());
        $sprint->close(30, new \DateTime());
        $this->adapter->getSprintRepository()->saveSprint($sprint);
        $this->getEntityManager()->clear();

        $sprint = $this->adapter->getSprintRepository()->sprintWithName($projectId, $sprintName);
        $this->assertInstanceOf(Sprint::class, $sprint);
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
    public function test_should_not_authorize_duplicate_sprint_name_for_team()
    {
        $sprint = SprintModel::pendingSprint(
            SprintId::uuid(),
            new SprintName('sprint-name'),
            ProjectId::fromString('test-project'),
            TeamId::fromString('team-id'),
            new \DateTime()
        );
        $this->adapter->getSprintRepository()->saveSprint($sprint);
        $this->adapter->getSprintRepository()->saveSprint(
            SprintModel::pendingSprint(
                SprintId::uuid(),
                $sprint->getName(),
                $sprint->projectId(),
                $sprint->teamId(),
                new \DateTime()
            )
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
        $person = $this->adapter->getPersonRepository()->personWithName(new PersonName('person-name'));

        $connection = $this->getEntityManager()->getConnection();
        $connection->insert(
            'backlog_team_members',
            [
                'member_id' => $person->getId()->toString(),
                'team_id' => $team->getId()->toString(),
            ]
        );
        $connection->insert(
            'backlog_team_members',
            [
                'member_id' => $person->getId()->toString(),
                'team_id' => $team->getId()->toString(),
            ]
        );
    }

    public function test_it_should_return_ended_sprints_of_project()
    {
        $projectOne = ProjectId::fromString('project-1');
        $projectTwo = ProjectId::fromString('project-2');

        $this->assertSprintIsCreated($sprintOne = SprintId::uuid(), $projectOne, TeamId::fromString('t1'));
        $this->assertStartedSprintIsCreated($sprintTwo = SprintId::uuid(), $projectOne);
        $this->assertEndedSprintIsCreated($sprintThree = SprintId::uuid(), $projectOne);

        $this->assertSprintIsCreated($sprintFour = SprintId::uuid(), $projectTwo, TeamId::fromString('t1'));
        $this->assertStartedSprintIsCreated($sprintFive = SprintId::uuid(), $projectTwo);
        $this->assertEndedSprintIsCreated($sprintSix = SprintId::uuid(), $projectTwo);

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
    }

    public function test_it_should_list_all_the_persons()
    {
        $this->assertCount(1, $this->adapter->getPersonRepository()->allRegistered());
    }

    public function test_it_should_list_all_the_teams()
    {
        $this->assertCount(1, $this->adapter->getTeamRepository()->allTeams());
    }

    public function test_it_should_list_all_the_sprints()
    {
        $this->assertCount(1, $this->adapter->getSprintRepository()->allSprints(new AllObjects()));
    }

    public function test_it_should_return_sprint_with_name_for_project()
    {
        /**
         * @var $projectRepository ProjectRepository
         */
        $projectRepository = $this->getEntityManager()->getRepository(ProjectAggregate::class);
        $secondProject = ProjectAggregate::emptyProject(
            ProjectId::fromString('other-project'), new ProjectName('other')
        );
        $projectRepository->saveProject($secondProject);

        /**
         * @var $sprintRepository SprintRepository
         */
        $sprintRepository = $this->getEntityManager()->getRepository(SprintModel::class);
        $sprints = $sprintRepository->allSprints(new AllObjects());
        $this->assertCount(1, $sprints);
        $name = $sprints[0]->getName();

        $sprintRepository->saveSprint(
            $secondProject->createSprint($expected = SprintId::uuid(), $name, TeamId::fromString('t1'), new \DateTime())
        );
        $this->getEntityManager()->clear();

        $this->assertEquals(
            $expected,
            $sprintRepository->sprintWithName($secondProject->getIdentity(), $name)->getId()
        );
    }

    /**
     * @param SprintId $sprintId
     * @param ProjectId $projectId
     * @param TeamId $teamId
     *
     * @return SprintModel
     */
    private function assertSprintIsCreated(SprintId $sprintId, ProjectId $projectId, TeamId $teamId)
    {
        $sprints = $this->adapter->getSprintRepository();
        $sprint = SprintModel::pendingSprint(
            $sprintId,
            new SprintName(uniqid()),
            $projectId,
            $teamId,
            new \DateTime()
        );

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
        $sprint = $this->assertSprintIsCreated($sprintId, $projectId, TeamId::fromString('t1'));
        $sprint->commit(MemberId::fromString('person-name'), ManDays::fromInt(3));
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
