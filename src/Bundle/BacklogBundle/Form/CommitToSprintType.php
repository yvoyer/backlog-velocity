<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Form;

use Star\BacklogVelocity\Bundle\BacklogBundle\Form\DataClass\CommitmentDataClass;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

final class CommitToSprintType extends AbstractType
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction($this->router->generate('sprint_commit'));
        $builder->add(
            'manDays',
            Type\IntegerType::class,
            [
                'attr' => [
                    'class' => 'form-control man_days',
                ],
                'label' => false,
                'constraints' => [
                    new Constraints\NotBlank(
                        [
                            'message' => 'validation.commitments.manDays.not_blank',
                        ]
                    ),
                    new Constraints\GreaterThan(
                        [
                            'value' => 0,
                            'message' => 'validation.commitments.manDays.greater_than'
                        ]
                    ),
                ],
                'error_bubbling' => true,
            ]
        );
        $builder->add(
            'memberId',
            Type\HiddenType::class
        );
        $builder->add(
            'sprintId',
            Type\HiddenType::class
        );
        $builder->add(
            'save',
            Type\SubmitType::class,
            [
                'attr' => [
                    'class' => 'btn btn-primary live_save',
                ],
                'label' => 'label.form.commitment.submit',
                'translation_domain' => 'messages',
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'commitment';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault(
            'attr',
            [
                'class' => $this->getBlockPrefix(),
            ]
        );
        $resolver->setDefault('data_class', CommitmentDataClass::class);
    }
}
