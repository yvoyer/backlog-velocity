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
     * @param string $memberId
     * @param string $sprintId
     */
    public function __construct(string $memberId, string $sprintId)
    {
        $this->memberId = $memberId;
        $this->sprintId = $sprintId;
    }
}
