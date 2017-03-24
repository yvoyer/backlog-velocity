<?php

namespace Star\Component\Sprint\Exception;

final class DeprecatedFeatureException extends \RuntimeException
{
    /**
     * @param string $method
     *
     * @return DeprecatedFeatureException
     */
    public static function methodDeprecated($method)
    {
        return new self("Method '{$method}'' is deprecated.");
    }
}
