<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityAlreadyExistsException;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\InvalidArgumentException;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class TeamModelTest extends TestCase
{
    /**
     * @var TeamModel
     */
    private $team;

    /**
     * @var PersonModel
     */
    private $person;

	protected function setUp(): void
    {
        $this->person = PersonModel::fromString('id', 'person-name');
        $this->team = TeamModel::create(TeamId::fromString('id'), new TeamName('name'));
    }

    public function test_should_return_the_id(): void
    {
        $this->assertSame('id', $this->team->getId()->toString());
    }

    public function test_should_return_the_name(): void
    {
        $this->assertSame('name', $this->team->getName()->toString());
    }

    public function test_should_add_member(): void
    {
        $this->assertCount(0, $this->team->getTeamMembers());
        $this->team->addTeamMember($this->person);
        $this->assertCount(1, $this->team->getTeamMembers());

        $this->assertContainsOnly(MemberId::class, $this->team->getTeamMembers());
    }

    /**
     * @ticket #46
     */
    public function test_should_not_add_an_already_added_member(): void
    {
        $this->team->addTeamMember($this->person);

        $this->expectException(EntityAlreadyExistsException::class);
        $this->expectExceptionMessage("Person 'id' is already part of team.");
        $this->team->addTeamMember($this->person);
    }

    public function test_should_have_a_valid_name(): void
    {
	    $this->expectException(InvalidArgumentException::class);
	    $this->expectExceptionMessage("The name can't be empty.");
        TeamModel::create(TeamId::fromString('id'), new TeamName(''));
    }

    public function test_it_should_return_the_team_members(): void
    {
        $this->team->addTeamMember(PersonModel::fromString('id-1', 'name-1'));
        $this->team->addTeamMember(PersonModel::fromString('id-2', 'name-2'));
        $this->team->addTeamMember(PersonModel::fromString('id-3', 'name-3'));
        $this->assertCount(3, $this->team->getTeamMembers());
        $this->assertContainsOnlyInstancesOf(MemberId::class, $members = $this->team->getTeamMembers());
        $this->assertEquals('id-1', $members[0]->toString());
        $this->assertEquals('id-2', $members[1]->toString());
        $this->assertEquals('id-3', $members[2]->toString());
    }
}
