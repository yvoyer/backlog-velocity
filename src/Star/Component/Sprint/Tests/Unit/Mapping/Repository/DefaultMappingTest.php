<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Mapping\Repository;

use Star\Component\Sprint\Mapping\Repository\DefaultMapping;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\SprintMemberData;
use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Mapping\TeamMemberData;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class DefaultMappingTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Mapping\Repository\DefaultMapping
 *
 * @covers Star\Component\Sprint\Mapping\Repository\DefaultMapping
 */
class DefaultMappingTest extends UnitTestCase
{
    /**
     * @var DefaultMapping
     */
    private $mapping;

    public function setUp()
    {
        $this->mapping = new DefaultMapping();
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
            array(TeamData::LONG_NAME, 'getTeamMapping'),
            array(SprintData::LONG_NAME, 'getSprintMapping'),
            array(SprinterData::LONG_NAME, 'getSprinterMapping'),
            array(TeamMemberData::LONG_NAME, 'getTeamMemberMapping'),
            array(SprintMemberData::LONG_NAME, 'getSprintMemberMapping'),
        );
    }
}
