<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Exception;

use Star\Component\Sprint\Exception\EntityAlreadyExistsException;
use Star\Component\Sprint\Exception\EntityNotFoundException;
use Star\Component\Sprint\Exception\InvalidArgumentException;
use Star\Component\Sprint\Exception\Sprint\SprintNotClosedException;

/**
 * Class ExceptionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $object
     *
     * @dataProvider providePackageException
     */
    public function test_be_an_exception($object)
    {
        $this->assertInstanceOf('Exception', $object, 'Should be a php exception.');
        $this->assertInstanceOf('Star\Component\Sprint\Exception\BacklogException', $object, 'Should be an exception of the package.');
    }

    public function providePackageException()
    {
        return array(
            array(new InvalidArgumentException()),
            array(new EntityNotFoundException()),
            array(new SprintNotClosedException()),
            array(new EntityAlreadyExistsException()),
        );
    }
}
