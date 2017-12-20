<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Form;

use Star\Component\Sprint\Application\BacklogBundle\Form\DataClass\SprintVelocityDataClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints;

final class CloseSprintType extends AbstractType
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     */
    public function __construct(RouterInterface $router, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var SprintVelocityDataClass $data
         */
        $data = $options['data'];
        $builder->setAction($this->router->generate('sprint_end', ['sprintId' => $data->sprintId]));
        $builder->setMethod('PATCH');
        $builder->add(
            'velocity',
            Type\IntegerType::class,
            [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.form.end_sprint.velocity',
                'label_attr' => [
                    'class' => 'sr-only',
                ],
                'constraints' => [
                    new Constraints\NotBlank(
                        [
                            'message' => 'validation.sprint.actual_velocity.not_blank',
                        ]
                    ),
                    new Constraints\GreaterThan(
                        [
                            'value' => 0,
                            'message' => 'validation.sprint.actual_velocity.greater_than',
                        ]
                    ),
                ],
                'error_bubbling' => true,
            ]
        );
        $builder->add(
            $this->translator->trans('label.form.end_sprint.submit'),
            Type\SubmitType::class,
            [
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', SprintVelocityDataClass::class);
    }
}
