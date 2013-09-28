<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\EntityInterface;
use Star\Component\Sprint\Entity\MemberInterface;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\SprintInterface;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamInterface;
use Star\Component\Sprint\Entity\TeamMember;

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
            $this->setId($team, $aInfo['id']);

            $result[] = $team;
        }

        return $result;
    }

    /**
     * Set the id of the class.
     *
     * @param object  $object
     * @param integer $id
     */
    private function setId($object, $id)
    {
        $reflexion = new \ReflectionClass(get_class($object));
        $property = $reflexion->getProperty('id');
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
        if (count($mandatoryFields) !== count($diff)) {
            foreach ($mandatoryFields as $field) {
                if (false === array_search($field, $diff)) {
                    throw new \InvalidArgumentException(
                        "The field '$field' is defined as mandatory, but was not found on dataset. " . json_encode($data)
                    );
                }
            }
        }
    }

    /**
     * Create an object of $type.
     *
     * @param string $type
     *
     * @return EntityInterface
     */
    public function createObject($type)
    {
        // TODO: Implement createObject() method.
    }

    /**
     * Create a member object.
     *
     * @return MemberInterface
     */
    public function createMember()
    {
        // TODO: Implement createMember() method.
    }

    /**
     * Create a sprint object.
     *
     * @return SprintInterface
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
     * @return TeamInterface
     */
    public function createTeam($name)
    {
        $team = new Team($name);

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
        // TODO: Implement createSprinter() method.
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
