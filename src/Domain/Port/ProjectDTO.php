<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Port;

final class ProjectDTO
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @param string $id
     * @param string $name
     */
    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}
