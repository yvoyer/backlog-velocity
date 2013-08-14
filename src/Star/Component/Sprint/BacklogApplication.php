<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint;

use Star\Component\Sprint\Command\Team\AddCommand;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Repository\YamlFileRepository;
use Symfony\Component\Console\Application;

/**
 * Class BacklogApplication
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint
 */
class BacklogApplication extends Application
{
    /**
     * @param string $dataFolder The base data folder path.
     */
    public function __construct($dataFolder)
    {
        parent::__construct('backlog', '0.1');
        $this->add(new AddCommand(new TeamRepository(new YamlFileRepository($dataFolder, 'teams'))));
    }
}
