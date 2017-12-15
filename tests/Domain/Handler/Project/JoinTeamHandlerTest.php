<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Project;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\TeamModel;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\TeamCollection;

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
            $this->teams = new TeamCollection()
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
}
