<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Project;

use Star\BacklogVelocity\Agile\Application\Query\TeamDTO;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine\DbalQueryHandlerTest;

/**
 * @group functional
 */
final class TeamWithIdentityHandlerTest extends DbalQueryHandlerTest
{
    /**
     * @var TeamId
     */
    private $id;

    public function doFixtures()
    {
        $this->id = $this->createTeam('team-name')->getId();
    }

    public function test_it_should_throw_exception_when_not_found(): void
    {
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(
            "Object of class 'Star\BacklogVelocity\Agile\Domain\Model\Team' with identity 'tid' could not be found."
        );
        $this->handle(
            new TeamWithIdentityHandler($this->connection),
            new TeamWithIdentity(TeamId::fromString('tid'))
        );
    }

    public function test_it_should_return_the_team_with_identity(): void
    {
        $team = $this->handle(
            new TeamWithIdentityHandler($this->connection),
            new TeamWithIdentity($this->id)
        );

        $this->assertInstanceOf(TeamDTO::class, $team);
    }
}
