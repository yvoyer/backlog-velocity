<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Project;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\Team;
use Star\BacklogVelocity\Agile\Domain\Model\TeamModel;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\PersonCollection;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\TeamCollection;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Null\NullPerson;

final class JoinTeamHandlerTest extends TestCase
{
    /**
     * @var JoinTeamHandler
     */
    private $handler;

    /**
     * @var TeamCollection
     */
    private $teams;

    public function setUp()
    {
        $this->handler = new JoinTeamHandler(
            $this->teams = new TeamCollection(),
            new PersonCollection([new NullPerson('mid')])
        );
    }

    public function test_it_should_add_member_to_team()
    {
        $this->teams->saveTeam(TeamModel::fromString('tid', 'tname'));

        $handler = $this->handler;
        $handler(JoinTeam::fromString('tid', 'mid'));

        $this->assertCount(1, $this->teams);
        $this->assertInstanceOf(Team::class, $team = $this->teams->findOneByName('tname'));
        $this->assertCount(1, $team->getTeamMembers());
    }

    public function test_it_should_throw_exception_when_team_do_not_exists()
    {
        $handler = $this->handler;
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(
            "Object of class 'Star\BacklogVelocity\Agile\Domain\Model\Team' with identity 'not-found' could not be found."
        );
        $handler(JoinTeam::fromString('not-found', 'mid'));
    }

    public function test_it_should_throw_exception_when_person_do_not_exists()
    {
        $this->teams->saveTeam(TeamModel::fromString('tid', 'tname'));

        $handler = $this->handler;
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(
            "Object of class 'Star\BacklogVelocity\Agile\Domain\Model\Member' with identity 'not-found' could not be found."
        );
        $handler(JoinTeam::fromString('tid', 'not-found'));
    }
}
