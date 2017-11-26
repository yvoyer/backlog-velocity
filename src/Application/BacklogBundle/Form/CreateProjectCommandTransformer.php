<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Form;

use Star\Component\Sprint\Application\BacklogBundle\Form\DataClass\ProjectDataClass;
use Star\Component\Sprint\Domain\Handler\CreateProject;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\ProjectName;
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
            $value = new CreateProject(ProjectId::uuid(), new ProjectName($value->name));
        }

        return $value;
    }
}
