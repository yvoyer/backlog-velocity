<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Form;

use Star\BacklogVelocity\Agile\Application\Command\Project\CreateProject;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectName;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\DataClass\ProjectDataClass;
use Symfony\Component\Form\DataTransformerInterface;

final class CreateProjectCommandTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     */
    public function transform($value)
    {
        if (! $value instanceof ProjectDataClass) {
            $value = new ProjectDataClass();
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
            // todo use IdGenerator->projectId()
            $value = new CreateProject(ProjectId::uuid(), new ProjectName($value->name));
        }

        return $value;
    }
}
