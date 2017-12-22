<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Common\Model;

interface Attribute
{
    /**
     * @return string
     */
    public function attributeName() :string;

    /**
     * @return string
     */
    public function attributeValue() :string;
}
