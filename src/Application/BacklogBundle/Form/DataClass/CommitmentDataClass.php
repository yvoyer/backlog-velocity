<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Form\DataClass;

final class CommitmentDataClass
{
    /**
     * @var string
     */
    public $memberId;

    /**
     * @var string
     */
    public $sprintId;

    /**
     * @var int
     */
    public $manDays;

    /**
     * @var string
     */
    public $personName;

    /**
     * @param string $memberId
     * @param string $sprintId
     * @param string $personName
     * @param int|null $manDays
     */
    public function __construct(string $memberId, string $sprintId, string $personName, int $manDays = null)
    {
        $this->memberId = $memberId;
        $this->sprintId = $sprintId;
        $this->personName = $personName;
        $this->manDays = $manDays;
    }
}
