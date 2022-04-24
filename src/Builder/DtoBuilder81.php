<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Builder;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;

final class DtoBuilder81 extends DtoBuilder
{
    protected function buildClass(string $name): Class_
    {
        return $this->builder
            ->class($name)
            ->addStmt(
                $this->builder->method('__construct')
                    ->makePublic()
                    ->addParams(
                        array_map(
                            static fn (Field $field): Node\Param => new Node\Param(
                                var:   new Node\Expr\Variable($field->getName()),
                                type:  $field->getType(),
                                flags: Class_::MODIFIER_PUBLIC | Class_::MODIFIER_READONLY,
                            ),
                            $this->fields,
                        )
                    )
                    ->getNode()
            )
            ->getNode();
    }
}
