<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Form\Transformer;

use Star\BacklogVelocity\Agile\Application\Command\Project\CreateTeam;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\DataClass\TeamDataClass;
use Symfony\Component\Form\DataTransformerInterface;

final class CreateTeamTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     */
    public function transform($value)
    {
        if (! $value instanceof TeamDataClass) {
            $value = new TeamDataClass();
        }

        return $value;
    }

    /**
     * @param mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     */
    public function reverseTransform($value)
    {
        if (isset($value->name)) {
            // todo use IdGenerator->teamId()
            $value = CreateTeam::fromString(TeamId::uuid()->toString(), $value->name);
        }

        return $value;
    }
}
