<?php

namespace Star\Component\Sprint\Domain\Exception;

use Assert\InvalidArgumentException as BaseException;

final class InvalidAssertionException extends BaseException implements BacklogException
{
}
