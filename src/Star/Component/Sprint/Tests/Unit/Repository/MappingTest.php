<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository;

use Star\Component\Sprint\Repository\Mapping;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class MappingTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository
 *
 * @covers Star\Component\Sprint\Repository\Mapping
 */
class MappingTest extends UnitTestCase
{
    /**
     * @var
     */
    private $mapping;

    public function setUp()
    {
        $this->mapping = new Mapping(
            'TeamRepository',
            'SprintRepository',
            'SprinterRepository',
            'TeamMemberRepository',
            'SprintMemberRepository'
        );
    }

    /**
     * @dataProvider provideRepositoryMappingData
     *
     * @param $repository
     * @param $method
     */
    public function testShouldReturnTheConfiguredMapping($repository, $method)
    {
        $this->assertSame($repository, $this->mapping->{$method}());
    }

    public function provideRepositoryMappingData()
    {
        return array(
            array('TeamRepository', 'getTeamMapping'),
            array('SprintRepository', 'getSprintMapping'),
            array('SprinterRepository', 'getSprinterMapping'),
            array('TeamMemberRepository', 'getTeamMemberMapping'),
            array('SprintMemberRepository', 'getSprintMemberMapping'),
        );
    }
}
