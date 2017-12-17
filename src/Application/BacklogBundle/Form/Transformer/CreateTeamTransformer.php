<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Form\Transformer;

use Star\Component\Sprint\Application\BacklogBundle\Form\DataClass\TeamDataClass;
use Star\Component\Sprint\Domain\Handler\Project\CreateTeam;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
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
            $value = CreateTeam::fromString(TeamId::uuid()->toString(), $value->name);
        }

        return $value;
    }
}
