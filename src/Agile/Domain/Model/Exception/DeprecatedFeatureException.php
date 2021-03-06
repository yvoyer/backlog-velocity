<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model\Exception;

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
