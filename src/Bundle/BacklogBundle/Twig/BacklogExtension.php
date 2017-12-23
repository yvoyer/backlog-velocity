<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Twig;

use Star\BacklogVelocity\Agile\Application\Query\CommitmentDTO;
use Star\BacklogVelocity\Agile\Application\Query\SprintDTO;
use Star\BacklogVelocity\Agile\Application\Query\TeamMemberDTO;
use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintStatus;
use Star\BacklogVelocity\Agile\Domain\Model\VelocityCalculator;
use Star\BacklogVelocity\Cli\BacklogApplication;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\CloseSprintType;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\CommitToSprintType;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\CreateSprintType;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\DataClass\SprintVelocityDataClass;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\DataClass\CommitmentDataClass;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\DataClass\CreateSprintDataClass;
use Star\BacklogVelocity\Bundle\BacklogBundle\Form\StartSprintType;
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
     * @var VelocityCalculator
     */
    private $calculator;

    /**
     * @param FormFactory $factory
     * @param RequestStack $stack
     * @param VelocityCalculator $calculator
     */
    public function __construct(FormFactory $factory, RequestStack $stack, VelocityCalculator $calculator)
    {
        $this->factory = $factory;
        $this->stack = $stack;
        $this->calculator = $calculator;
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
            new TwigFunction('startSprintForm', [$this, 'startSprintForm']),
            new TwigFunction('endSprintForm', [$this, 'endSprintForm']),
            new TwigFunction('memberCommitment', [$this, 'commitmentOf']),
            new TwigFunction('createSprintForm', [$this, 'createSprintForm']),
            new TwigFunction('estimatedVelocity', [$this, 'estimatedVelocity']),
            new TwigFunction('focusFactor', [$this, 'focusFactor']),
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

    public function commitForm(SprintDTO $sprint, TeamMemberDTO $member, array $commitments) :FormView
    {
        $form = $this->factory->create(
            CommitToSprintType::class,
            new CommitmentDataClass(
                $member->personId,
                $sprint->id,
                $member->personName,
                $this->commitmentOf($commitments, $member->personId)
            )
        );
        $form->handleRequest($this->stack->getCurrentRequest());

        return $form->createView();
    }

    public function createSprintForm(string $projectId) :FormView
    {
        $data = new CreateSprintDataClass();
        $data->project = $projectId;

        $form = $this->factory->create(
            CreateSprintType::class,
            $data,
            [
                'block_name' => 'sprint-' . $projectId,
            ]
        );
        $form->handleRequest($this->stack->getCurrentRequest());

        return $form->createView();
    }

    public function startSprintForm(SprintDTO $sprint) :FormView
    {
        $data = new SprintVelocityDataClass();
        $data->sprintId = $sprint->id;
        $data->velocity = $this->estimatedVelocity($sprint->id);

        $form = $this->factory->create(
            StartSprintType::class,
            $data,
            [
                'block_name' => 'sprint-' . $sprint->id,
            ]
        );
        $form->handleRequest($this->stack->getCurrentRequest());

        return $form->createView();
    }

    public function endSprintForm(SprintDTO $sprint) :FormView
    {
        $data = new SprintVelocityDataClass();
        $data->sprintId = $sprint->id;

        $form = $this->factory->create(
            CloseSprintType::class,
            $data,
            [
                'block_name' => 'sprint-' . $sprint->id,
            ]
        );
        $form->handleRequest($this->stack->getCurrentRequest());

        return $form->createView();
    }

    /**
     * @param string $sprintId
     *
     * @return int
     */
    public function estimatedVelocity(string $sprintId) :int
    {
        return $this->calculator->calculateEstimatedVelocity(SprintId::fromString($sprintId))->toInt();
    }

    public function focusFactor(string $sprintId) :float
    {
        return $this->calculator->calculateCurrentFocus(SprintId::fromString($sprintId));
    }

    /**
     * @param CommitmentDTO[] $commitments
     * @param string $id
     *
     * @return int
     */
    public function commitmentOf(array $commitments, string $id) :int
    {
        foreach ($commitments as $commitment) {
            if (MemberId::fromString($id)->matchIdentity($commitment->memberId())) {
                return $commitment->manDays;
            }
        }

        return 0;
    }
}