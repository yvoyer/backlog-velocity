<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Question;

use Symfony\Component\Console\Question\Question;

/**
 * Class AskManDaysQuestion
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Question
 */
class AskManDaysQuestion extends Question
{
    public function __construct()
    {
        parent::__construct('How many man days will he be available?');
    }
}
 