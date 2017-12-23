<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Form;

use Prooph\ServiceBus\QueryBus;
use Star\BacklogVelocity\Agile\Application\Query\Project\AllTeams;
use Star\BacklogVelocity\Agile\Application\Query\TeamDTO;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\DataClass\CreateSprintDataClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Router;

final class CreateSprintType extends AbstractType
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var QueryBus
     */
    private $query_bus;

    /**
     * @param Router $router
     * @param QueryBus $query_bus
     */
    public function __construct(Router $router, QueryBus $query_bus)
    {
        $this->router = $router;
        $this->query_bus = $query_bus;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $promise = $this->query_bus->dispatch(new AllTeams());
        $result = [];
        $promise->done(function (array $r) use (&$result) {
            $result = $r;
        });

        $choices = array_combine(
            array_map(
                function (TeamDTO $team) {
                    return $team->name;
                },
                $result
            ),
            array_map(
                function (TeamDTO $team) {
                    return $team->id;
                },
                $result
            )
        );
        $builder->setAction($this->router->generate('sprint_create'));
        $builder->add(
            'team',
            Type\ChoiceType::class,
            [
                'required' => true,
                'label' => 'label.form.create_sprint.team',
                'translation_domain' => 'messages',
                'choices' => $choices,
                'label_attr' => [
                    'class' => 'sr-only',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ]
        );
        $builder->add(
            'project',
            Type\HiddenType::class,
            [
                'required' => true,
            ]
        );
        $builder->add(
            'save',
            Type\SubmitType::class,
            [
                'label' => 'label.form.create_sprint.submit',
                'translation_domain' => 'messages',
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', CreateSprintDataClass::class);
    }
}
