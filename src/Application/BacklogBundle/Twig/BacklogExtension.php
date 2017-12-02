<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Twig;

use Star\BacklogVelocity\Application\Cli\BacklogApplication;
use Star\Component\Sprint\Application\BacklogBundle\Form\CommitToSprintType;
use Star\Component\Sprint\Application\BacklogBundle\Form\DataClass\CommitmentDataClass;
use Star\Component\Sprint\Domain\Model\SprintStatus;
use Star\Component\Sprint\Domain\Port\SprintDTO;
use Star\Component\Sprint\Domain\Port\TeamMemberDTO;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class BacklogExtension extends \Twig_Extension
{
    /**
     * @var FormFactory
     */
    private $factory;

    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * @param FormFactory $factory
     * @param RequestStack $stack
     */
    public function __construct(FormFactory $factory, RequestStack $stack)
    {
        $this->factory = $factory;
        $this->stack = $stack;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('ucfirst', [$this, 'ucfirst']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('backlog_version', [$this, 'version']),
            new TwigFunction('sprint_badge', [$this, 'sprintBadge']),
            new TwigFunction('commitForm', [$this, 'commitForm']),
        ];
    }

    public function ucfirst(string $string) :string
    {
        return ucfirst($string);
    }

    /**
     * @return string
     */
    public function version() :string
    {
        return BacklogApplication::VERSION;
    }

    public function sprintBadge(SprintDTO $sprint) :string
    {
        switch ($sprint->status()) {
            case SprintStatus::PENDING:
                return 'info';
            case SprintStatus::STARTED:
                return 'warning';
            case SprintStatus::CLOSED:
                return 'success';
        }

        throw new \InvalidArgumentException("Badge for status '{$sprint->status()}' is not supported.");
    }

    public function commitForm(SprintDTO $sprint, TeamMemberDTO $member) :FormView
    {
        $form = $this->factory->create(
            CommitToSprintType::class,
            new CommitmentDataClass($member->personId, $sprint->id)
        );
//        $form->handleRequest($this->stack->getCurrentRequest());


        return $form->createView();
    }
}
