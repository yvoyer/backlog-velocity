<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Mapping\Entity;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\TeamData;

/**
 * Class ArrayDataObjectFactory
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Factory
 */
class ArrayDataObjectFactory implements EntityCreatorInterface
{
    /**
     * The data to use for the factory.
     *
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;

        if (false === empty($this->data)) {
            $this->validateMandatoryFields($this->data, array('id', 'name'));
        }
    }

    /**
     * Builds the teams based on $data.
     *
     * @return Team[]
     */
    public function createTeams()
    {
        $result = array();
        foreach ($this->data as $aInfo) {
            $team = $this->createTeam($aInfo['name']);
            $this->setProperty($team, 'id', $aInfo['id']);

            $result[] = $team;
        }

        return $result;
    }

    /**
     * Returns all the sprinters.
     *
     * @return SprinterData[]
     */
    public function findAllSprinters()
    {
        $result = array();
        foreach ($this->data as $aInfo) {
            $sprinter = $this->createSprinter();
            $this->setProperty($sprinter, 'id', $aInfo['id']);
            $this->setProperty($sprinter, 'name', $aInfo['name']);

            $result[] = $sprinter;
        }


        return $result;
    }

    /**
     * Set the id of the class.
     *
     * @param object  $object
     * @param string  $property
     * @param integer $id
     */
    private function setProperty($object, $property, $id)
    {
        $reflexion = new \ReflectionClass(get_class($object));
        $property = $reflexion->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($object, $id);
    }

    /**
     * Validates whether the $data contains all the $mandatoryFields.
     *
     * @param array $data
     * @param array $mandatoryFields
     *
     * @throws \InvalidArgumentException
     */
    private function validateMandatoryFields(array $data, array $mandatoryFields)
    {
        $diff = array_intersect_key($mandatoryFields, $this->data);
        foreach ($mandatoryFields as $field) {
            if (false === array_search($field, $diff)) {
                throw new \InvalidArgumentException(
                    "The field '$field' is defined as mandatory, but was not found on dataset. " . json_encode($data)
                );
            }
        }
    }

    /**
     * Create an object of $type.
     *
     * @param string $type
     *
     * @return Entity
     */
    public function createObject($type)
    {
        // TODO: Implement createObject() method.
    }

    /**
     * Create a sprint object.
     *
     * @return Sprint
     */
    public function createSprint()
    {
        // TODO: Implement createSprint() method.
    }

    /**
     * Create a team object.
     *
     * @param string $name The name of the team.
     *
     * @return Team
     */
    public function createTeam($name)
    {
        $team = new TeamData($name);

        return $team;
    }

    /**
     * Create a SprintMember.
     *
     * @return SprintMember
     */
    public function createSprintMember()
    {
        // TODO: Implement createSprintMember() method.
    }

    /**
     * Create a Sprinter.
     *
     * @return Sprinter
     */
    public function createSprinter()
    {
        return new SprinterData('');
    }

    /**
     * Create a TeamMember.
     *
     * @return TeamMember
     */
    public function createTeamMember()
    {
        // TODO: Implement createTeamMember() method.
    }
}
