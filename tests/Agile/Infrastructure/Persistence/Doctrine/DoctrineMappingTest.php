<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Model\AllObjects;
use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
use Star\BacklogVelocity\Agile\Domain\Model\ManDays;
use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Model\Person;
use Star\BacklogVelocity\Agile\Domain\Model\PersonModel;
use Star\BacklogVelocity\Agile\Domain\Model\PersonName;
use Star\BacklogVelocity\Agile\Domain\Model\Project;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectAggregate;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectName;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectRepository;
use Star\BacklogVelocity\Agile\Domain\Model\Sprint;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintModel;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Agile\Domain\Model\Team;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamName;
use Star\BacklogVelocity\Agile\Domain\Model\Velocity;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 * @group functional
 */
final class DoctrineMappingTest extends TestCase
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

        $project = ProjectAggregate::emptyProject(
            ProjectId::fromString('test-project'), new ProjectName('Project test')
        );
        $em->persist($project);
        $em->flush();

        $em->persist($team = $project->createTeam(TeamId::fromString('t1'), new TeamName('team-name')));
        $em->flush();

        $em->persist($person = PersonModel::fromString('person-name', 'person-name'));
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

        $root = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
        $config = Setup::createXMLMetadataConfiguration(array($root . '/src/Agile/Infrastructure/Persistence/Doctrine/Resources/mappings'), true);

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
        $this->assertSame(0, $sprint->getPlannedVelocity()->toInt());
        $this->assertSame(0, $sprint->getActualVelocity()->toInt());
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
        $this->assertSame(10, $sprint->getPlannedVelocity()->toInt());
        $this->assertSame(0, $sprint->getActualVelocity()->toInt());
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
        $sprint->close(Velocity::fromInt(30), new \DateTime());
        $this->adapter->getSprintRepository()->saveSprint($sprint);
        $this->getEntityManager()->clear();

        $sprint = $this->adapter->getSprintRepository()->sprintWithName($projectId, $sprintName);
        $this->assertInstanceOf(Sprint::class, $sprint);
        $this->assertFalse($sprint->isStarted(), 'Sprint should not be started');
        $this->assertTrue($sprint->isClosed(), 'Sprint should be closed');
        $this->assertSame(10, $sprint->getPlannedVelocity()->toInt());
        $this->assertSame(30, $sprint->getActualVelocity()->toInt());
        $this->assertSame(5, $sprint->getManDays()->toInt());
        $this->assertCount(1, $sprint->getCommitments());
        $this->assertGreaterThan(0, $sprint->getFocusFactor()->toInt());
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

    public function test_it_should_return_past_focus_from_ended_sprints_of_team()
    {
        $projectOne = ProjectId::fromString('project-1');
        $projectTwo = ProjectId::fromString('project-2');

        $teamId = TeamId::fromString('t1');
        $this->assertSprintIsCreated($sprintOne = SprintId::uuid(), $projectOne, $teamId);
        $this->assertStartedSprintIsCreated($sprintTwo = SprintId::uuid(), $projectOne);
        $s3 = $this->assertEndedSprintIsCreated($sprintThree = SprintId::uuid(), $projectOne);

        $this->assertSprintIsCreated($sprintFour = SprintId::uuid(), $projectTwo, $teamId);
        $this->assertStartedSprintIsCreated($sprintFive = SprintId::uuid(), $projectTwo);
        $s6 = $this->assertEndedSprintIsCreated($sprintSix = SprintId::uuid(), $projectTwo);

        $this->getEntityManager()->clear();

        $sprints = $this->adapter->getSprintRepository();
        $result = $sprints->estimatedFocusOfPastSprints($teamId, new \DateTime());

        $this->assertContainsOnlyInstancesOf(FocusFactor::class, $result);
        $this->assertCount(2, $result);

        $this->assertEquals(
            [
                $s3->getFocusFactor(),
                $s6->getFocusFactor(),
            ],
            $result
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
        $sprint->close(Velocity::fromInt(mt_rand()), new \DateTime());
        $sprints->saveSprint($sprint);

        return $sprint;
    }
}
