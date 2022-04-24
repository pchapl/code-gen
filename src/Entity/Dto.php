<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Entity;

use Pchapl\CodeGen\EntityInterface;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;

final class Dto implements EntityInterface
{
    public function __construct(private Class_|Namespace_ $node)
    {
    }

    public function getNode(): Class_|Namespace_
    {
        return $this->node;
    }
}
