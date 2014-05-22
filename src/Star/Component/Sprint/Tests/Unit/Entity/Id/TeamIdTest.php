<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Id;

use Star\Component\Sprint\Entity\Id\TeamId;

/**
 * Class TeamIdTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Id
 *
 * @covers Star\Component\Sprint\Entity\Id\TeamId
 * @uses Star\Component\Sprint\Type\String
 */
class TeamIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TeamId
     */
    private $id;

    public function setUp()
    {
        $this->id = new TeamId('name');
    }

    public function testShouldReturnTheStringId()
    {
        $this->assertSame('name', (string) $this->id);
    }
}
 