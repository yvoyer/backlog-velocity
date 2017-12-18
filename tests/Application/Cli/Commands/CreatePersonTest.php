<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Infrastructure\Persistence\Collection\PersonCollection;
use Star\Component\Sprint\Domain\Entity\Factory\BacklogModelTeamFactory;
use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Model\PersonName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class CreatePersonTest extends CliIntegrationTestCase
{
    /**
     * @var BacklogModelTeamFactory
     */
    private $factory;

    /**
     * @var PersonCollection
     */
    private $personRepository;

    /**
     * @var CreatePerson
     */
    private $command;

    public function setUp()
    {
        $this->personRepository = new PersonCollection();
        $this->factory = new BacklogModelTeamFactory();

        $this->command = new CreatePerson($this->personRepository, $this->factory);
    }

    public function test_should_be_a_command()
    {
        $this->assertInstanceOfCommand($this->command, 'backlog:person:add', 'Add a person.');
    }

    public function test_should_have_a_name_argument()
    {
        $this->assertCommandHasArgument($this->command, 'name', null, true);
    }

    public function test_should_add_person()
    {
        $this->assertFalse($this->personRepository->personWithNameExists(new PersonName('person-name')));
        $content = $this->executeCommand($this->command, array('name' => 'person-name'));
        $this->assertContains("The person 'person-name' was successfully saved.", $content);
        $this->assertInstanceOf(Person::class, $this->personRepository->personWithName(new PersonName('person-name')));
    }

    public function test_should_not_add_person_when_already_exists()
    {
        $this->personRepository->savePerson($this->factory->createPerson('person-name'));
        $content = $this->executeCommand($this->command, array('name' => 'person-name'));
        $this->assertContains("The person 'person-name' already exists.", $content);
    }
}
