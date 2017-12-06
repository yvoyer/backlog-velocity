<?php declare(strict_types=1);

namespace Star\Component\Sprint\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;
use React\Promise\Deferred;
use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\ManDays;
use Star\Component\Sprint\Domain\Model\PersonModel;
use Star\Component\Sprint\Domain\Model\ProjectAggregate;
use Star\Component\Sprint\Domain\Model\ProjectName;
use Star\Component\Sprint\Domain\Model\SprintCommitment;
use Star\Component\Sprint\Domain\Model\SprintModel;
use Star\Component\Sprint\Domain\Model\SprintName;
use Star\Component\Sprint\Domain\Model\TeamMemberModel;
use Star\Component\Sprint\Domain\Model\TeamModel;
use Star\Component\Sprint\Domain\Query\Query;

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

    final public function setUp()
    {
        $this->connection = DriverManager::getConnection(array(
                'driver' => 'pdo_sqlite',
                'memory' => true,
            )
        );

        $root = dirname(dirname(dirname(dirname(__DIR__)))) . '/plugin/Doctrine';
        $config = Setup::createXMLMetadataConfiguration(array($root . '/Resources/config/doctrine'), true);

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

    protected function createTeam(string $name, Project $project) :Team
    {
        $this->em->persist($team = TeamModel::fromString($name, $name, $project));
        $this->em->flush();

        return $team;
    }

    protected function createTeamMember(Person $person, Team $team)
    {
        $this->em->persist($team->addTeamMember($person));
        $this->em->flush();
    }

    protected function createPendingSprint(string $name, Project $project) :Sprint
    {
        $sprint = $project->createSprint(
            SprintId::fromString($name),
            new SprintName($name),
            new \DateTime()
        );

        $this->em->persist($sprint);
        $this->em->flush();

        return $sprint;
    }

    protected function createStartedSprint(string $name, Project $project) :Sprint
    {
        $sprint = $this->createPendingSprint($name, $project);
        $sprint->commit(MemberId::fromString('m1'), ManDays::fromInt(12));
        $sprint->commit(MemberId::fromString('m2'), ManDays::fromInt(34));
        $sprint->commit(MemberId::fromString('m3'), ManDays::fromInt(56));
        $sprint->start(76, new \DateTime());

        $this->em->persist($sprint);
        $this->em->flush();

        return $sprint;
    }

    protected function createClosedSprint(string $name, Project $project) :Sprint
    {
        $sprint = $this->createPendingSprint($name, $project);
        $sprint->commit(MemberId::fromString('m1'), ManDays::fromInt(78));
        $sprint->commit(MemberId::fromString('m2'), ManDays::fromInt(90));
        $sprint->start(98, new \DateTime());
        $sprint->close(10, new \DateTime());

        $this->em->persist($sprint);
        $this->em->flush();

        return $sprint;
    }
}
