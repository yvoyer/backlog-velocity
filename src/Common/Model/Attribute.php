<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Common\Model;

interface Attribute
{
    public function attributeName(): string;

    public function attributeValue(): string;
}
