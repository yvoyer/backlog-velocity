<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Id;

use Star\Component\Sprint\Entity\Id\PersonId;

/**
 * Class TeamIdTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Id
 *
 * @covers Star\Component\Sprint\Entity\Id\PersonId
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
 