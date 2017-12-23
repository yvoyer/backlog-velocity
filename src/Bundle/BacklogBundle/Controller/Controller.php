<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

/**
 * Abstract class that makes sure we use the right injections
 */
abstract class Controller extends BaseController
{
    /**
     * @param string $type
     * @param string $message
     * @deprecated Use "@backlog.messages" service
     */
    protected function addFlash($type, $message)
    {
        throw new \RuntimeException(__METHOD__ . ' should not be used, use "@backlog.messages".');
    }
}
