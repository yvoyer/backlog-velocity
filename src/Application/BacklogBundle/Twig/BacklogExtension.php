<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Twig;

use Star\BacklogVelocity\Application\Cli\BacklogApplication;
use Star\Component\Sprint\Domain\Model\SprintStatus;
use Star\Component\Sprint\Domain\Port\SprintDTO;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class BacklogExtension extends \Twig_Extension
{
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
}
