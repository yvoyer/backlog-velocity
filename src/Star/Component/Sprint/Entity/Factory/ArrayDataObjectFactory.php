<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\Team;

/**
 * Class ArrayDataObjectFactory
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Factory
 */
class ArrayDataObjectFactory
{
    /**
     * Builds the teams based on $data.
     *
     * @param array $data
     *
     * @return Team[]
     */
    public function buildTeams(array $data)
    {
        $result = array();
        foreach ($data as $aInfo) {
            $result[] = $this->buildTeam($aInfo);
        }

        return $result;
    }

    /**
     * Return a team.
     *
     * @param array $data
     *
     * @return Team
     */
    public function buildTeam(array $data)
    {
        $this->validateMandatoryFields($data, array('id', 'name'));

        $team = new Team($data['name']);
        $this->setId($team, $data['id']);

        return $team;
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
        foreach ($mandatoryFields as $field) {
            if (false === isset($data[$field])) {
                throw new \InvalidArgumentException(
                    "The field '$field' is defined as mandatory, but was not found on dataset."
                );
            }
        }
    }
}
