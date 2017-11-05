<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BacklogBundle extends Bundle
{
    public function __construct()
    {
        $this->name = 'BacklogBundle';
    }
}
