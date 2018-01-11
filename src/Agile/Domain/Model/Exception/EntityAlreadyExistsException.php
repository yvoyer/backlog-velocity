<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model\Exception;

use Star\BacklogVelocity\Common\Model\Attribute;
use Star\Component\Identity\Identity;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class EntityAlreadyExistsException extends \Exception implements BacklogException
{
    /**
     * @param Identity $identity
     * @param Attribute $attribute
     *
     * @return EntityAlreadyExistsException
     */
    public static function withAttribute(Identity $identity, Attribute $attribute) :EntityAlreadyExistsException {
        return new self(
            sprintf(
                "Entity of type '%s' with '%s' equals to '%s' already exists.",
                $identity->entityClass(),
                $attribute->attributeName(),
                $attribute->attributeValue()
            )
        );
    }
}
