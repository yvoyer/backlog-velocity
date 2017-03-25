<?php

namespace Star\Component\Sprint\Exception;

use Assert\InvalidArgumentException as BaseException;

final class InvalidAssertionException extends BaseException implements BacklogException
{
}
