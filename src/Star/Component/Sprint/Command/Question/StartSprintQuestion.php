<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Question;

use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Entity\Sprint;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * Class StartSprintQuestion
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Question
 */
class StartSprintQuestion extends ChoiceQuestion
{
    /**
     * @param Sprint[]|SprintCollection $collection
     */
    public function __construct(SprintCollection $collection)
    {
        $options = array();
        foreach ($collection as $sprint) {
            $options[] = $sprint->getName();
        }

        parent::__construct('Select the sprint to start: ', $options);
    }
}
 