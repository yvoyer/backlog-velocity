<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Question;

use Star\Component\Sprint\Collection\PersonCollection;
use Star\Component\Sprint\Entity\Person;
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
     * @param Person[]|PersonCollection $collection
     */
    public function __construct(PersonCollection $collection)
    {
        $persons = array();
        foreach ($collection as $person) {
            $persons[] = $person->getName();
        }

        parent::__construct('Choose the user to add to the sprint:', $persons);
    }
}
 