<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Project;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityAlreadyExistsException;
use Star\BacklogVelocity\Agile\Domain\Model\TeamModel;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\TeamCollection;

final class CreateTeamTest extends TestCase
{
    /**
     * @var TeamCollection
     */
    private $teams;

	protected function setUp(): void
    {
        $this->teams = new TeamCollection();
    }

    public function test_it_should_create_a_team(): void
    {
        $this->assertCount(0, $this->teams);

        $handler = new CreateTeamHandler($this->teams);
        $handler(CreateTeam::fromString('t1', 'Team 1'));

        $this->assertCount(1, $this->teams);
    }

    public function test_it_should_throw_exception_when_team_with_name_exists(): void
    {
        $this->teams->saveTeam(TeamModel::fromString('t1', 'Team 1'));
        $this->assertCount(1, $this->teams);

        $handler = new CreateTeamHandler($this->teams);

        $this->expectException(EntityAlreadyExistsException::class);
        $this->expectExceptionMessage(
            "Entity of type 'Star\BacklogVelocity\Agile\Domain\Model\Team' with 'name' equals to 'Team 1' already exists."
        );
        $handler(CreateTeam::fromString('t1', 'Team 1'));
    }
}
