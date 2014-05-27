<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Entity\Id;

use Star\Component\Sprint\Entity\Id\SprintId;
use tests\UnitTestCase;

/**
 * Class SprintIdTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Entity\Id
 *
 * @covers Star\Component\Sprint\Entity\Id\SprintId
 * @uses Star\Component\Sprint\Type\String
 */
class SprintIdTest extends UnitTestCase
{
    /**
     * @var SprintId
     */
    private $id;

    public function setUp()
    {
        $this->id = new SprintId('name');
    }

    public function testShouldReturnTheStringId()
    {
        $this->assertSame('name', (string) $this->id);
    }
}
 