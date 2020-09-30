<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle;

use Star\BacklogVelocity\Bundle\BacklogBundle\DependencyInjection\Compiler\MessageRegistrationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BacklogBundle extends Bundle
{
    public function __construct()
    {
        $this->name = 'BacklogBundle';
    }

    public function build(ContainerBuilder $container): void {
    	$container->addCompilerPass(new MessageRegistrationPass());
    }
}
