<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository\Team;

use Star\Component\Sprint\Entity\EntityInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlFileRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository\Team
 */
class YamlFileRepository
{
    /**
     * The root folder of the project.
     *
     * @var string
     */
    private $root;

    /**
     * The full filename.
     *
     * @var string
     */
    private $filename;

    /**
     * @param string $root     The root folder where to dump the data.
     * @param string $fileName The filename in which to dump the data (no extension).
     */
    public function __construct($root, $fileName)
    {
        $this->root = $root;

        $folder         = $this->root . '/data';
        $this->filename = $folder . "/{$fileName}.yml";

        if (false === file_exists($folder)) {
            mkdir($folder);
        }

        if (false === file_exists($this->filename)) {
            file_put_contents($this->filename, '');
        }
    }

    /**
     * Save the $object in the repository.
     *
     * @param EntityInterface $object
     */
    public function save(EntityInterface $object)
    {
        $data = Yaml::parse($this->filename);
        // @todo Check if already exists.
        $data[$object->getIdentifier()->getKey()] = $object->toArray();
        file_put_contents($this->filename, Yaml::dump($data));
    }
}
