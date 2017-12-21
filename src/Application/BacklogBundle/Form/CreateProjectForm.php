<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Form;

use Star\Component\Sprint\Application\BacklogBundle\Form\DataClass\ProjectDataClass;
use Star\Component\Sprint\Domain\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Domain\Model\ProjectName;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContext;

final class CreateProjectForm extends AbstractType
{
    /**
     * @var ProjectRepository
     */
    private $projects;

    /**
     * @param ProjectRepository $projects
     */
    public function __construct(ProjectRepository $projects)
    {
        $this->projects = $projects;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            Type\TextType::class,
            [
                'required' => false,
                'label' => 'label.form.project.name',
                'translation_domain' => 'messages',
                'constraints' => [
                    new Constraints\NotBlank(
                        [
                            'message' => 'validation.project.name.not_blank',
                        ]
                    ),
                    new Constraints\Length(
                        [
                            'min' => 3,
                            'minMessage' => 'validation.project.name.min_length',
                        ]
                    ),
                    new Constraints\Callback(
                        [
                            'callback' => function ($object, ExecutionContext $context, $payload) {
                                if (empty($object)) { return; }
                                if ($this->projects->projectExists(new ProjectName($object))) {
                                    $context->addViolation('validation.project.name.exists', ['value' => $object]);
                                }
                            }
                        ]
                    )
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ]
        );
        $builder->add(
            'save',
            Type\SubmitType::class,
            [
                'label' => 'title.project.create',
                'translation_domain' => 'messages',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ]
        );
        $builder->setAttribute('class', 'form-inline');
        $builder->addViewTransformer(new CreateProjectCommandTransformer());
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'project';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', ProjectDataClass::class);
    }
}
