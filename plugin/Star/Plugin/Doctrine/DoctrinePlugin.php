<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine;

use Doctrine\Common\Persistence\ObjectManager as DoctrineManager;
use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Entity\ObjectManager;
use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Mapping\Repository\DefaultMapping;
use Star\Component\Sprint\Plugin\BacklogPlugin;
use Star\Component\Sprint\Repository\RepositoryManager;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DoctrinePlugin
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine
 */
class DoctrinePlugin implements BacklogPlugin
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $objectManager;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @param DoctrineManager $objectManager
     * @param OutputInterface $output
     */
    public function __construct(DoctrineManager $objectManager, OutputInterface $output)
    {
        $this->objectManager = $objectManager;
        $this->output        = $output;
    }

    /**
     * Returns the entity creator.
     *
     * @return EntityCreator
     */
    public function getEntityCreator()
    {
        return new InteractiveObjectFactory(new DialogHelper(), $this->output);
    }

    /**
     * Returns the entity finder.
     *
     * @return EntityFinder
     */
    public function getEntityFinder()
    {
        return new DoctrineObjectFinder($this->getRepositoryManager());
    }

    /**
     * Returns the repository manager.
     *
     * @return RepositoryManager
     */
    public function getRepositoryManager()
    {
        return new DoctrineObjectManagerAdapter($this->objectManager, new DefaultMapping());
    }

    /**
     * Returns the object manager.
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return new ObjectManager($this->getEntityCreator(), $this->getEntityFinder());
    }
}
 