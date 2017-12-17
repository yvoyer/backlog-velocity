<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Form;

use Star\Component\Sprint\Application\BacklogBundle\Form\DataClass\CommitmentDataClass;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints;

final class CommitToSprintType extends AbstractType
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param Router $router
     * @param TranslatorInterface $translator
     */
    public function __construct(Router $router, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $personName = $options['data']->personName;

        $builder->setAction($this->router->generate('sprint_commit'));
        $builder->add(
            'manDays',
            Type\IntegerType::class,
            [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => $personName,
                'label_attr' => [
                    'sr-only',
                ],
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
            $this->translator->trans('label.form.commitment.submit'),
            Type\SubmitType::class,
            [
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
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
