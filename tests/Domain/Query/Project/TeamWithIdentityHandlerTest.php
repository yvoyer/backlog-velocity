<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Project;

use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Port\TeamDTO;
use Star\Component\Sprint\Infrastructure\Persistence\Doctrine\DbalQueryHandlerTest;

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

    public function test_it_should_throw_exception_when_not_found()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(
            "Object of class 'Star\Component\Sprint\Domain\Entity\Team' with identity 'tid' could not be found."
        );
        $this->handle(
            new TeamWithIdentityHandler($this->connection),
            new TeamWithIdentity(TeamId::fromString('tid'))
        );
    }

    public function test_it_should_return_the_team_with_identity()
    {
        $team = $this->handle(
            new TeamWithIdentityHandler($this->connection),
            new TeamWithIdentity($this->id)
        );

        $this->assertInstanceOf(TeamDTO::class, $team);
    }
}
