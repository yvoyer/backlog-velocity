<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Entity\Id;

use Star\Component\Sprint\Entity\Id\PersonId;

/**
 * Class TeamIdTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Entity\Id
 *
 * @covers Star\Component\Sprint\Entity\Id\PersonId
 * @uses Star\Component\Sprint\Type\String
 */
class PersonIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PersonId
     */
    private $id;

    public function setUp()
    {
        $this->id = new PersonId('name');
    }

    public function testShouldReturnTheStringId()
    {
        $this->assertSame('name', (string) $this->id);
    }
}
 