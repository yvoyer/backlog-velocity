<?php
///**
// * This file is part of the backlog-velocity project.
// *
// * (c) Yannick Voyer (http://github.com/yvoyer)
// */
//
//namespace tests\Type;
//
//use Star\Component\Sprint\Type\String;
//
///**
// * Class StringIdTest
// *
// * @author  Yannick Voyer (http://github.com/yvoyer)
// *
// * @package tests\Type
// *
// * @covers Star\Component\Sprint\Type\String
// */
//class StringTest extends \PHPUnit_Framework_TestCase
//{
//    /**
//     * @var String
//     */
//    private $id;
//
//    public function setUp()
//    {
//        $this->id = new String('some name');
//    }
//    public function testShouldReturnTheStringRepresentation()
//    {
//        $this->assertSame('some name', (string) $this->id);
//    }
//
//    /**
//     * @param $value
//     *
//     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
//     * @expectedExceptionMessage The value should be a non empty string.
//     *
//     * @dataProvider provideInvalidValues
//     */
//    public function testShouldThrowExceptionWithInvalidValue($value)
//    {
//        new String($value);
//    }
//
//    public function provideInvalidValues()
//    {
//        return array(
//            array(''),
//            array(12.34),
//            array(false),
//            array(true),
//            array(null),
//            array(array()),
//            array(new \stdClass()),
//        );
//    }
//}
