<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Form;

use Star\BacklogVelocity\Agile\Domain\Model\TeamName;
use Star\BacklogVelocity\Agile\Domain\Model\TeamRepository;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\DataClass\TeamDataClass;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\Transformer\CreateTeamTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContext;

final class CreateTeamType extends AbstractType
{
    /**
     * @var TeamRepository
     */
    private $teams;

    /**
     * @param TeamRepository $teams
     */
    public function __construct(TeamRepository $teams)
    {
        $this->teams = $teams;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            Type\TextType::class,
            [
                'required' => false,
                'label' => 'label.form.team.name',
                'translation_domain' => 'messages',
                'constraints' => [
                    new Constraints\NotBlank(
                        [
                            'message' => 'validation.team.name.not_blank',
                        ]
                    ),
                    new Constraints\Length(
                        [
                            'min' => 3,
                            'minMessage' => 'validation.team.name.min_length',
                        ]
                    ),
                    new Constraints\Callback(
                        [
                            'callback' => function ($object, ExecutionContext $context, $payload) {
                                if (empty($object)) { return; }
                                if ($this->teams->teamWithNameExists(new TeamName($object))) {
                                    $context->addViolation('validation.team.name.exists', ['<name>' => $object]);
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
                'label' => 'title.team.create',
                'translation_domain' => 'messages',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ]
        );
        $builder->setAttribute('class', 'form-inline');
        $builder->addViewTransformer(new CreateTeamTransformer());
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'team';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', TeamDataClass::class);
    }
}
