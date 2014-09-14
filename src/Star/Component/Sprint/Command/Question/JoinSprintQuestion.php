<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Question;

use Star\Component\Sprint\Collection\SprintMemberCollection;
use Star\Component\Sprint\Entity\SprintMember;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * Class JoinSprintQuestion
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Question
 */
class JoinSprintQuestion extends ChoiceQuestion
{
    /**
     * @param SprintMember[]|SprintMemberCollection $collection
     */
    public function __construct(SprintMemberCollection $collection)
    {
        $sprintMembers = array();
        foreach ($collection as $sprintMember) {
            $sprintMembers[] = $sprintMember->getName();
        }

        parent::__construct('Choose the user to add to the sprint:', $sprintMembers);
    }
}
 