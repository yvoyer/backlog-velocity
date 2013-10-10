<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Factory;

use Star\Component\Sprint\Entity\Factory\ArrayDataObjectFactory;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class TeamBuilderTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Factory
 *
 * @covers Star\Component\Sprint\Entity\Factory\ArrayDataObjectFactory
 */
class TeamBuilderTest extends UnitTestCase
{
    /**
     * @param array $data
     *
     * @return ArrayDataObjectFactory
     */
    private function getFactory(array $data = array())
    {
        return new ArrayDataObjectFactory($data);
    }

    public function testShouldBuildTheSuppliedTeams()
    {
        $data = array(
            array(
                'id' => 1,
                'name' => 'The Galactic Empire',
            ),
            array(
                'id' => 2,
                'name' => 'The Rebel Alliance',
            ),
        );
        $result = $this->getFactory($data)->createTeams();

        $this->assertCount(2, $result);

        $team1 = $result[0];
        $this->assertInstanceOfTeam($team1);
        $this->assertSame(1, $team1->getId(), 'The id is not as expected');
        $this->assertSame('The Galactic Empire', $team1->getName(), 'The name is not as expected');

        $team2 = $result[1];
        $this->assertInstanceOfTeam($team2);
        $this->assertSame(2, $team2->getId(), 'The id is not as expected');
        $this->assertSame('The Rebel Alliance', $team2->getName(), 'The name is not as expected');
    }

    public function testShouldReturnEmptyResultWhenDataEmpty()
    {
        $this->assertCount(0, $this->getFactory()->createTeams());
    }

    /**
     * @dataProvider providesMandatoryFields
     *
     * @param string $expectedField
     * @param array  $data
     */
    public function testShouldThrowExceptionWhenMandatoryFieldMissing($expectedField, array $data)
    {
        $this->setExpectedException(
            '\InvalidArgumentException',
            "The field '$expectedField' is defined as mandatory, but was not found on dataset."
        );
        $this->getFactory($data);
    }

    public function providesMandatoryFields()
    {
        return array(
            'The id should be mandatory' => array('id', array('name' => 'name')),
            'The name should be mandatory' => array('name', array(array('id' => 1))),
        );
    }

    public function testShouldBeEntityFactory()
    {
        $this->assertInstanceOfEntityCreator($this->getFactory());
    }

    public function testShouldReturnTheSprinters()
    {
        $data = array(
            array(
                'id'   => 1,
                'name' => 'Darth Vader',
            ),
            array(
                'id'   => 2,
                'name' => 'Luke Skywalker',
            ),
        );

        $sprinters = $this->getFactory($data)->findAllSprinters();
        $this->assertCount(2, $sprinters);

        $sprinter = $sprinters[0];
        $this->assertInstanceOfSprinter($sprinter);
        $this->assertSame(1, $sprinter->getId());
        $this->assertSame('Darth Vader', $sprinter->getName());

        $sprinter = $sprinters[1];
        $this->assertInstanceOfSprinter($sprinter);
        $this->assertSame(2, $sprinter->getId());
        $this->assertSame('Luke Skywalker', $sprinter->getName());
    }
}
