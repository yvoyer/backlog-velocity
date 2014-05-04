<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Mapping;

use Star\Component\Sprint\Collection\SprinterCollection;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Entity\Team;

/**
 * Class SprintDataTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Mapping
 *
 * @covers Star\Component\Sprint\Mapping\SprintData
 */
class SprintDataTest extends AbstractValueProvider
{
    /**
     * @var SprintData
     */
    private $sut;

    /**
     * @var Team
     */
    private $team;

    /**
     * @var SprinterCollection
     */
    private $sprinterCollection;

    public function setUp()
    {
        $this->team = $this->getMockTeam();
        $this->sprinterCollection = new SprinterCollection();

        $this->sut  = new SprintData('Sprint', $this->team, 40, 30, 20);
    }

    public function testShouldReturnTheName()
    {
        $this->assertSame('Sprint', $this->sut->getName());
    }

    public function testShouldReturnTheManDays()
    {
        $this->assertSame(40, $this->sut->getManDays());
    }

    public function testShouldReturnTheEstimatedVelocity()
    {
        $this->assertSame(30, $this->sut->getEstimatedVelocity());
    }

    public function testShouldReturnTheActualVelocity()
    {
        $this->assertSame(20, $this->sut->getActualVelocity());
    }

    public function testShouldReturnTheFocusFactor()
    {
        $this->assertSame(50, $this->sut->getFocusFactor());
    }

    public function testShouldBeEntity()
    {
        $this->assertInstanceOfEntity($this->sut);
    }

    public function testShouldBeSprint()
    {
        $this->assertInstanceOfSprint($this->sut);
    }

    public function testShouldReturnTheArrayRepresentation()
    {
        $expected = array(
            'id'   => null,
            'name' => 'Sprint',
        );

        $this->assertSame($expected, $this->sut->toArray());
    }

    public function testShouldReturnsTheTeam()
    {
        $this->assertSame($this->team, $this->sut->getTeam());
    }

    /**
     * @dataProvider providerValidNames
     *
     * @param $name
     */
    public function testShouldBeValid($name)
    {
        $this->sut = new SprintData($name, $this->team);
        $this->assertTrue($this->sut->isValid());
    }

    /**
     * @dataProvider providerInvalidNames
     *
     * @param $name
     */
    public function testShouldNotBeValid($name)
    {
        $this->sut = new SprintData($name, $this->team);
        $this->assertFalse($this->sut->isValid());
    }

    public function testShouldSetAndGetTheName()
    {
        $name = 'name';
        $sut  = $this->sut;

        $this->assertNotSame($name, $sut->getName());
        $sut->setName($name);
        $this->assertSame($name, $sut->getName());
    }

    public function testReturnWhetherTheSprintIsOpen()
    {
        $this->assertFalse($this->sut->isOpen());
        $this->sut->start($this->sprinterCollection);
        $this->assertTrue($this->sut->isOpen());
        $this->sut->close(1);
        $this->assertFalse($this->sut->isOpen());
    }

    public function testReturnWhetherTheSprintIsClosed()
    {
        $this->assertFalse($this->sut->isClosed());
        $this->sut->start($this->sprinterCollection);
        $this->assertFalse($this->sut->isClosed());
        $this->sut->close(2);
        $this->assertTrue($this->sut->isClosed());
    }

    public function testShouldSetTheActualVelocity()
    {
        $this->sut->start($this->sprinterCollection);
        $this->sut->close(2);
        $this->assertSame(2, $this->sut->getActualVelocity());
    }
}
