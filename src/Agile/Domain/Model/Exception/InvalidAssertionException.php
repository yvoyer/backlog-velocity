<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model\Exception;

use Assert\InvalidArgumentException as BaseException;

final class InvalidAssertionException extends BaseException implements BacklogException
{
}
