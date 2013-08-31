<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Sprinter;

use Star\Component\Sprint\Entity\EntityInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Class AddCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Sprinter
 */
class AddCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('backlog:sprinter:add');
        $this->setDescription('Add a sprinter');
    }
}
