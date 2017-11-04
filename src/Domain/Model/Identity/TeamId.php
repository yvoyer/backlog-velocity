<?php

namespace Star\Component\Sprint\Model\Identity;

use Behat\Transliterator\Transliterator;
use Star\Component\Sprint\Exception\BacklogAssertion;

final class TeamId
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
        BacklogAssertion::string($id, 'Team id "%s" expected to be string, type %s given.');
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
     * @return TeamId
     */
    public static function fromString($string)
    {
        return new self(Transliterator::urlize($string));
    }
}
