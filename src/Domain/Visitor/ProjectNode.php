<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Visitor;

interface ProjectNode
{
    /**
     * @param ProjectVisitor $visitor
     */
    public function acceptProjectVisitor(ProjectVisitor $visitor);
}
