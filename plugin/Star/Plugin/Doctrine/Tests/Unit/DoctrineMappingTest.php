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
    }

    public function test_should_persist_team()
    {
        $factory = new BacklogModelTeamFactory();
        $team = $factory->createTeam('team-name');

        $this->save($team);
        /**
         * @var $team Team
         */
        $team = $this->getRefreshedObject($team);

        $this->assertInstanceOfTeam($team);
        $this->assertSame('team-name', $team->getName(), 'Name is not as expected');

        return $team;
    }

    /**
     * @depends test_should_persist_team
     */
    public function test_should_persist_person(Team $team)
    {
        $person = new PersonModel('person-name');
        $this->save($person);
        $refreshPerson = $this->getRefreshedObject($person);
        $this->getRefreshedObject($team);
        $this->assertInstanceOfPerson($refreshPerson);

        return $person;
    }

    /**
     * @depends test_should_persist_team
     */
    public function test_should_persist_sprint(Team $team)
    {
        $this->assertRowContainsCount(0, SprintModel::CLASS_NAME);
        $sprint = $team->createSprint('sprint-name');
        $this->assertInstanceOfSprint($sprint);
        $em = $this->getEntityManager();
        $em->persist($team);
        $em->persist($sprint);
        $em->flush();
        $this->assertRowContainsCount(1, SprintModel::CLASS_NAME);

        /**
         * @var $team Team
         */
        $team = $this->getRefreshedObject($team);
        $this->assertAttributeCount(1, 'sprints', $team);

        return $this->getRefreshedObject($sprint);
    }

    /**
     * @depends test_should_persist_team
     * @depends test_should_persist_person
     */
    public function test_should_persist_team_member(Team $team, Person $person)
    {
        /**
         * @var Team $team
         */
        $team = $this->getRefreshedObject($team);
        $person = $this->getRefreshedObject($person);

        $teamMember = $team->addTeamMember($person);
        $this->assertInstanceOfTeamMember($teamMember);
        $em = $this->getEntityManager();
        $em->persist($team);
        $em->persist($person);
        $em->persist($teamMember);
        $em->flush();
        $refreshTeamMember = $this->getRefreshedObject($teamMember);
        $this->assertInstanceOfTeamMember($refreshTeamMember);

        return $teamMember;
    }

    /**
     * @depends test_should_persist_team_member
     * @depends test_should_persist_sprint
     */
    public function test_should_persist_sprint_member(TeamMember $teamMember, Sprint $sprint)
    {
        $teamMember = $this->getRefreshedObject($teamMember);
        $this->assertRowContainsCount(0, SprintMemberModel::LONG_NAME);
        $sprintMember = $sprint->commit($teamMember, 54);
        $this->assertInstanceOfSprintMember($sprintMember);

        $em = $this->getEntityManager();
        $em->persist($sprintMember);
        $em->persist($teamMember);
        $em->persist($sprint);
        $em->flush();

        $this->assertRowContainsCount(1, SprintMemberModel::LONG_NAME);
    }

    /**
     * @param object $entity
     */
    private function save($entity)
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
     * @param object $object
     *
     * @return object
     */
    protected function getRefreshedObject($object)
    {
        $em = $this->getEntityManager();
        $em->clear();

        $id = $object->getId();
        $this->assertNotNull($id, 'The id should not be null');

        return $em->find(get_class($object), $id);
    }

    protected function assertRowContainsCount($count, $class)
    {
        $this->assertCount($count, $this->getEntityManager()->getRepository($class)->findAll());
    }
}
