<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Project;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Exception\EntityAlreadyExistsException;
use Star\Component\Sprint\Domain\Model\TeamModel;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\TeamCollection;

final class CreateTeamTest extends TestCase
{
    /**
     * @var TeamCollection
     */
    private $teams;

    public function setUp()
    {
        $this->teams = new TeamCollection();
    }

    public function test_it_should_create_a_team()
    {
        $this->assertCount(0, $this->teams);

        $handler = new CreateTeamHandler($this->teams);
        $handler(CreateTeam::fromString('t1', 'Team 1'));

        $this->assertCount(1, $this->teams);
    }

    public function test_it_should_throw_exception_when_team_with_name_exists()
    {
        $this->teams->saveTeam(TeamModel::fromString('t1', 'Team 1'));
        $this->assertCount(1, $this->teams);

        $handler = new CreateTeamHandler($this->teams);

        $this->expectException(EntityAlreadyExistsException::class);
        $this->expectExceptionMessage(
            "Entity of type 'Star\Component\Sprint\Domain\Entity\Team' with 'name' equals to 'Team 1' already exists."
        );
        $handler(CreateTeam::fromString('t1', 'Team 1'));
    }
}
