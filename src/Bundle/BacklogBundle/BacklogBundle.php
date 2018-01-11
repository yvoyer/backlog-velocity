<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BacklogBundle extends Bundle
{
    public function __construct()
    {
        $this->name = 'BacklogBundle';
    }
}
