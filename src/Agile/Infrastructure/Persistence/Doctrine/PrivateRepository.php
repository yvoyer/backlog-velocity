<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;

final class PrivateRepository
{
    public function __construct(ClassMetadata $metadata)
    {
        throw new \RuntimeException("The repository for the class '{$metadata->getName()}' is private.");
    }
}
