<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model\Exception;

use PHPUnit\Framework\TestCase;

/**
 * Class ExceptionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class ExceptionTest extends TestCase
{
    /**
     * @param $object
     *
     * @dataProvider providePackageException
     */
    public function test_be_an_exception($object)
    {
        $this->assertInstanceOf('Exception', $object, 'Should be a php exception.');
        $this->assertInstanceOf(BacklogException::class, $object, 'Should be an exception of the package.');
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
