<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Functional;

use Star\Component\Sprint\Entity\EntityInterface;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;

/**
 * Class DoctrineMappingTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Functional
 */
class DoctrineMappingTest extends FunctionalTestCase
{
    /**
     * Returns a refreshed object containing data from db.
     *
     * @param EntityInterface $object
     *
     * @return EntityInterface
     */
    private function getRefreshedObject(EntityInterface $object)
    {
        $em = $this->getEntityManager();
        $em->clear();

        $id = $object->getId();
        $this->assertNotNull($id, 'The id should not be null');

        return $em->find(get_class($object), $id);
    }

    public function testShouldPersistTeam()
    {
        $name = uniqid('team-name-');
        $team = new Team($name);

        $em = $this->getEntityManager();
        $em->persist($team);
        $em->flush();

        /**
         * @var $team Team
         */
        $team = $this->getRefreshedObject($team);

        $this->assertSame($name, $team->getName(), 'Name is not as expected');
    }

    public function testShouldPersistSprinter()
    {
        $name     = uniqid('sprinter-name-');
        $sprinter = new Sprinter($name);

        $em = $this->getEntityManager();
        $em->persist($sprinter);
        $em->flush();

        /**
         * @var $sprinter Sprinter
         */
        $sprinter = $this->getRefreshedObject($sprinter);

        $this->assertSame($name, $sprinter->getName(), 'Name is not as expected');
    }
}
