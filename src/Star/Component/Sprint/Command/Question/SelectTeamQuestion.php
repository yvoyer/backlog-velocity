<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Question;

use Star\Component\Sprint\Collection\TeamCollection;
use Star\Component\Sprint\Entity\Team;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * Class SelectTeamQuestion
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Question
 */
class SelectTeamQuestion extends ChoiceQuestion
{
    /**
     * @param Team[]|TeamCollection $collection
     * @param string $question
     */
    public function __construct(TeamCollection $collection, $question = 'Select a team: ')
    {
        $options = array();
        foreach ($collection as $team) {
            $options[] = $team->getName();
        }

        parent::__construct($question, $options);
    }
}
