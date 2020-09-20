<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model\Event;

final class NotImplementEventCallback extends \RuntimeException {
	public function __construct($event) {
		parent::__construct(\sprintf('The event "%s" is not handled yet.', \get_class($event)));
	}
}
