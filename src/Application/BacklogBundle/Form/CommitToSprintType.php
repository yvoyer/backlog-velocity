<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Form;

use Star\Component\Sprint\Application\BacklogBundle\Form\DataClass\CommitmentDataClass;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        /**
         * @var CommitmentDataClass $data
         */
        $data = $options['data'];
        $builder->setAttribute('id', $this->getBlockPrefix() . '-' . $data->memberId);
        $builder->setAttribute('class', 'form-inline');
        $builder->setAction($this->router->generate('sprint_commit', ['sprintId' => $data->sprintId]));
        $builder->add(
            'manDays',
            Type\IntegerType::class,
            [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'sr-only',
                ],
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
            'form.commit.submit',
            Type\ButtonType::class,
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
        $resolver->setDefault('data_class', CommitmentDataClass::class);
    }
}
