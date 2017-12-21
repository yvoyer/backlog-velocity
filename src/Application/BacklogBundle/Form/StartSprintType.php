<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Form;

use Star\Component\Sprint\Application\BacklogBundle\Form\DataClass\SprintVelocityDataClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints;

final class StartSprintType extends AbstractType
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var SprintVelocityDataClass $data
         */
        $data = $options['data'];
        $builder->setAction($this->router->generate('sprint_start', ['sprintId' => $data->sprintId]));
        $builder->setMethod('PUT');
        $builder->add(
            'velocity',
            Type\IntegerType::class,
            [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.form.start_sprint.velocity',
                'translation_domain' => 'messages',
                'label_attr' => [
                    'class' => 'sr-only',
                ],
                'constraints' => [
                    new Constraints\NotBlank(
                        [
                            'message' => 'validation.sprint.estimated_velocity.not_blank',
                        ]
                    ),
                    new Constraints\GreaterThan(
                        [
                            'value' => 0,
                            'message' => 'validation.sprint.estimated_velocity.greater_than',
                        ]
                    ),
                ],
                'error_bubbling' => true,
            ]
        );
        $builder->add(
            'save',
            Type\SubmitType::class,
            [
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
                'label' => 'label.form.start_sprint.submit',
                'translation_domain' => 'messages',
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', SprintVelocityDataClass::class);
    }
}
