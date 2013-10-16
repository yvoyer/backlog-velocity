<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Mapping\Repository;

use Star\Component\Sprint\Mapping\Repository\ConfigurableMapping;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class ConfigurableMappingTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Mapping\Repository
 *
 * @covers Star\Component\Sprint\Mapping\Repository\ConfigurableMapping
 */
class ConfigurableMappingTest extends UnitTestCase
{
    /**
     * @var ConfigurableMapping
     */
    private $mapping;

    public function setUp()
    {
        $this->mapping = new ConfigurableMapping(
            'TeamRepository',
            'SprintRepository',
            'SprinterRepository',
            'TeamMemberRepository',
            'SprintMemberRepository'
        );
    }

    public function testShouldBeARepositoryMapping()
    {
        $this->assertInstanceOf('Star\Component\Sprint\Mapping\Repository\Mapping', $this->mapping);
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
