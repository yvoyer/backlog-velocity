<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository;

use Star\Component\Sprint\Entity\EntityInterface;
use Star\Component\Sprint\Repository\Repository;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlFileRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository
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
     * The remote data contained in the file.
     *
     * @var¸array
     */
    private $remoteData;

    /**
     * @param string $root     The root folder where to dump the data.
     * @param string $fileName The filename in which to dump the data (no extension).
     */
    public function __construct($root, $fileName)
    {
        $this->root = $root;

        $folder         = $this->root;
        $this->filename = $folder . "/{$fileName}.yml";

        // Create folder
        if (false === file_exists($folder)) {
            mkdir($folder);
        }

        // Create file
        if (false === file_exists($this->filename)) {
            file_put_contents($this->filename, '');
        }

        $this->data       = array();
        $this->remoteData = array();
    }

    /**
     * Load the remote data from source.
     */
    private function loadRemote()
    {
        $this->remoteData = Yaml::parse($this->filename);

        if (empty($this->remoteData)) {
            $this->remoteData = array();
        }
    }

    /**
     * Save the $object in the repository.
     */
    public function save()
    {
        $dataToSave = array_merge($this->data, $this->remoteData);
        // @todo Check if already exists.
        file_put_contents($this->filename, Yaml::dump($dataToSave));
        $this->loadRemote();
    }

    /**
     * Returns all the object from one repository.
     *
     * @return EntityInterface[]
     */
    public function findAll()
    {
        $this->loadRemote();

        return $this->remoteData;
    }

    /**
     * Returns the object linked with the $id.
     *
     * @param mixed $id
     *
     * @return EntityInterface
     */
    public function find($id)
    {
        $this->loadRemote();

        $object = null;
        if (isset($this->remoteData[$id])) {
            $object = $this->remoteData[$id];
        }

        return $object;
    }

    /**
     * Add the $object linked to the $id.
     *
     * @param EntityInterface $object
     */
    public function add(EntityInterface $object)
    {
        $this->data[$object->getId()] = $object->toArray();
    }
}
