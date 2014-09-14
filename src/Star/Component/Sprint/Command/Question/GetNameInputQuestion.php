<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Question;

use Symfony\Component\Console\Question\Question;

/**
 * Class GetNameInputQuestion
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Question
 */
class GetNameInputQuestion extends Question
{
    public function __construct($question)
    {
        $this->setValidator(function($input) {
            if (empty($input)) {
                throw new \InvalidArgumentException('Name cannot be empty');
            }

            return $input;
        });

        parent::__construct('<info>' . $question . '</info>');
    }
}
 