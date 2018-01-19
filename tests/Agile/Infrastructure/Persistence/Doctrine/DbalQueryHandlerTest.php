<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;
use React\Promise\Deferred;
use Star\BacklogVelocity\Agile\Domain\Model\ManDays;
use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Model\Person;
use Star\BacklogVelocity\Agile\Domain\Model\PersonModel;
use Star\BacklogVelocity\Agile\Domain\Model\Project;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectAggregate;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectName;
use Star\BacklogVelocity\Agile\Domain\Model\Sprint;
use Star\BacklogVelocity\Agile\Domain\Model\SprintCommitment;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintModel;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Agile\Domain\Model\Team;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamMemberModel;
use Star\BacklogVelocity\Agile\Domain\Model\TeamModel;
use Star\BacklogVelocity\Agile\Domain\Model\Velocity;
use Star\BacklogVelocity\Common\Application\Query;

abstract class DbalQueryHandlerTest extends TestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var SprintRepository
     */
    protected $sprints;

    final public function setUp()
    {
        $this->connection = DriverManager::getConnection(array(
                'driver' => 'pdo_sqlite',
                'memory' => true,
            )
        );

        $root = dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/src/Agile/Infrastructure/Persistence/Doctrine';
        $config = Setup::createXMLMetadataConfiguration(array($root . '/Resources/mappings'), true);

        $this->em = EntityManager::create($this->connection, $config);
        $tool = new SchemaTool($this->em);
        $tool->dropDatabase();
        $tool->createSchema(
            [
                $this->em->getClassMetadata(ProjectAggregate::class),
                $this->em->getClassMetadata(PersonModel::class),
                $this->em->getClassMetadata(SprintModel::class),
                $this->em->getClassMetadata(SprintCommitment::class),
                $this->em->getClassMetadata(TeamModel::class),
                $this->em->getClassMetadata(TeamMemberModel::class),
            ]
        );

        $this->doFixtures();
        $this->em->clear();
        $this->sprints = $this->em->getRepository(SprintModel::class);
    }

    /**
     * @param callable $callable
     * @param Query $query
     *
     * @return mixed
     */
    protected function handle($callable, Query $query)
    {
        $callable($query, $promise = new Deferred());
        $result = null;
        $promise->promise()->done(function($value) use (&$result) {
            $result = $value;
        });

        return $result;
    }

    protected abstract function doFixtures();

    protected function createProject(string $name) :Project
    {
        $project = ProjectAggregate::emptyProject(
            ProjectId::fromString($name),
            new ProjectName($name)
        );

        $this->em->persist($project);
        $this->em->flush();

        return $project;
    }

    /**
     * @param string $name
     *
     * @return Person
     */
    protected function createPerson(string $name) :Person
    {
        $this->em->persist($person = PersonModel::fromString($name, $name));
        $this->em->flush();

        return $person;
    }

    protected function createTeam(string $name) :Team
    {
        $this->em->persist($team = TeamModel::fromString($name, $name));
        $this->em->flush();

        return $team;
    }

    protected function createTeamMember(Person $person, Team $team)
    {
        $this->em->persist($team->addTeamMember($person));
        $this->em->flush();
    }

    protected function createPendingSprint(string $name, Project $project, string $teamId) :Sprint
    {
        $sprint = $project->createSprint(
            SprintId::fromString($name),
            new SprintName($name),
            TeamId::fromString($teamId),
            new \DateTime()
        );

        $this->em->persist($sprint);
        $this->em->flush();

        return $sprint;
    }

    protected function createStartedSprint(string $name, Project $project, string $teamId) :Sprint
    {
        $sprint = $this->createPendingSprint($name, $project, $teamId);
        $sprint->commit(MemberId::fromString('m1'), ManDays::fromInt(12));
        $sprint->commit(MemberId::fromString('m2'), ManDays::fromInt(34));
        $sprint->commit(MemberId::fromString('m3'), ManDays::fromInt(56));
        $sprint->start(76, new \DateTime());

        $this->em->persist($sprint);
        $this->em->flush();

        return $sprint;
    }

    protected function createClosedSprint(string $name, Project $project, string $teamId) :Sprint
    {
        $sprint = $this->createPendingSprint($name, $project, $teamId);
        $sprint->commit(MemberId::fromString('m1'), ManDays::fromInt(78));
        $sprint->commit(MemberId::fromString('m2'), ManDays::fromInt(90));
        $sprint->start(98, new \DateTime());
        $sprint->close(Velocity::fromInt(10), new \DateTime());

        $this->em->persist($sprint);
        $this->em->flush();

        return $sprint;
    }

    protected function closeSprintWithId(Sprint $sprint, int $planned, int $actual, \DateTimeInterface $closedAt = null)
    {
        if (! $closedAt) {
            $closedAt = new \DateTimeImmutable();
        }

        $sprint->commit(MemberId::fromString('m1'), ManDays::fromInt(50));
        $sprint->start($planned, $closedAt);
        $sprint->close(Velocity::fromInt($actual), $closedAt);

        $this->em->persist($sprint);
        $this->em->flush();
    }

    protected function assertSprintCount(int $expected)
    {
        self::assertCount(
            $expected,
            $this->connection->fetchAll('SELECT * FROM backlog_sprints'),
            "The expected sprint count is not same"
        );
    }
}
