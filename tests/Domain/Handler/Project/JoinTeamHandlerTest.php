<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Project;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\TeamModel;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\PersonCollection;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\TeamCollection;
use Star\Plugin\Null\Entity\NullPerson;

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
            "Object of class 'Star\Component\Sprint\Domain\Entity\Team' with identity 'not-found' could not be found."
        );
        $handler(JoinTeam::fromString('not-found', 'mid'));
    }

    public function test_it_should_throw_exception_when_person_do_not_exists()
    {
        $this->teams->saveTeam(TeamModel::fromString('tid', 'tname'));

        $handler = $this->handler;
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(
            "Object of class 'Star\Component\Sprint\Domain\Model\Member' with identity 'not-found' could not be found."
        );
        $handler(JoinTeam::fromString('tid', 'not-found'));
    }
}
