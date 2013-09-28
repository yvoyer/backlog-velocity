<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Builder;

use Star\Component\Sprint\Builder\TeamBuilder;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class TeamBuilderTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Builder
 *
 * @covers Star\Component\Sprint\Builder\TeamBuilder
 */
class TeamBuilderTest extends UnitTestCase
{
    /**
     * @return TeamBuilder
     */
    private function getBuilder()
    {
        return new TeamBuilder();
    }

    public function testShouldBuilderTheSuppliedTeam()
    {
        $data = array(
            array(
                'id' => 1,
                'name' => 'The Galactic Empire',
            ),
        );
        $result = $this->getBuilder()->buildTeams($data);

        $this->assertCount(1, $result);
        /**
         * @var $team1 Team
         */
        $team1 = $result[0];
        $this->assertInstanceOfTeam($team1);
        $this->assertSame(1, $team1->getId(), 'The id is not as expected');
        $this->assertSame('The Galactic Empire', $team1->getName(), 'The name is not as expected');
    }

    /**
     * @dataProvider providesMandatoryFields
     *
     * @param $expectedField
     * @param array $data
     */
    public function testShouldThrowExceptionWhenMandatoryFieldMission($expectedField, array $data)
    {
        $this->setExpectedException(
            '\InvalidArgumentException',
            "The field '$expectedField' is defined as mandatory, but was not found on dataset."
        );
        $this->getBuilder()->buildTeam($data);
    }

    public function providesMandatoryFields()
    {
        return array(
            'The id should be mandatory' => array('id', array()),
            'The name should be mandatory' => array('name', array('id' => 1)),
        );
    }
}
