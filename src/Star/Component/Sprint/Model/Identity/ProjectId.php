<?php

namespace Star\Component\Sprint\Model\Identity;

use Assert\Assertion;
use Behat\Behat\Util\Transliterator;

final class ProjectId
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    private function __construct($id)
    {
        Assertion::string($id, 'Project id "%s" expected to be string, type %s given.');
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return strval($this->id);
    }

    /**
     * @param string $string
     *
     * @return ProjectId
     */
    public static function fromString($string)
    {
        return new self(Transliterator::urlize($string));
    }
}
