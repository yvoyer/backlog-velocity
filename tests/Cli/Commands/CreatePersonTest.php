<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Commands;

use Star\BacklogVelocity\Agile\Domain\Model\Person;
use Star\BacklogVelocity\Agile\Domain\Model\PersonModel;
use Star\BacklogVelocity\Agile\Domain\Model\PersonName;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\PersonCollection;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class CreatePersonTest extends CliIntegrationTestCase
{
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

        $this->command = new CreatePerson($this->personRepository);
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
        $this->personRepository->savePerson(PersonModel::fromString('person-id', 'person-name'));
        $content = $this->executeCommand($this->command, array('name' => 'person-name'));
        $this->assertContains("The person 'person-name' already exists.", $content);
    }
}
