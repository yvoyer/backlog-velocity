<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository\Team;

use Star\Component\Sprint\Entity\EntityInterface;
use Star\Component\Sprint\Entity\IdentifierInterface;
use Star\Component\Sprint\Repository\Repository;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlFileRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository\Team
 */
class YamlFileRepository implements Repository
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
     * The data in the file.
     *
     * @var array
     */
    private $data;

    /**
     * @param string $root     The root folder where to dump the data.
     * @param string $fileName The filename in which to dump the data (no extension).
     */
    public function __construct($root, $fileName)
    {
        $this->root = $root;

        $folder         = $this->root . '/data';
        $this->filename = $folder . "/{$fileName}.yml";

        // Create folder
        if (false === file_exists($folder)) {
            mkdir($folder);
        }

        // Create file
        if (false === file_exists($this->filename)) {
            file_put_contents($this->filename, '');
        }

        $this->data = Yaml::parse($this->filename);
    }

    /**
     * Save the $object in the repository.
     *
     * @param EntityInterface $object
     */
    public function save(EntityInterface $object)
    {
        $this->add($object->getIdentifier(), $object);
        // @todo Check if already exists.
        file_put_contents($this->filename, Yaml::dump($this->data));
    }

    /**
     * Returns all the object from one repository.
     *
     * @return EntityInterface[]
     */
    public function findAll()
    {
        return $this->data;
    }

    /**
     * Returns the object linked with the $id.
     *
     * @param IdentifierInterface $id
     *
     * @return EntityInterface
     */
    public function find(IdentifierInterface $id)
    {
        $object = null;
        $index  = $id->getKey();
        if (isset($this->data[$index])) {
            $object = $this->data[$index];
        }

        return $object;
    }

    /**
     * Add the $object linked to the $id.
     *
     * @param IdentifierInterface $id
     * @param EntityInterface     $object
     */
    public function add(IdentifierInterface $id, EntityInterface $object)
    {
        $this->data[$id->getKey()] = $object->toArray();
    }
}
