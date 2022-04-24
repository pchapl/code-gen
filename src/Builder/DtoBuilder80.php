<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Builder;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Return_;

final class DtoBuilder80 extends DtoBuilder
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
                                flags: Class_::MODIFIER_PRIVATE,
                            ),
                            $this->fields,
                        )
                    )
                    ->getNode()
            )
            ->addStmts(
                array_map(
                    fn (Field $field): Node\Stmt\ClassMethod => $this->builder
                        ->method('get' . ucfirst($field->getName()))
                        ->makePublic()
                        ->setReturnType($field->getType())
                        ->addStmt(
                            new Return_(new PropertyFetch(new Variable('this'), $field->getName()))
                        )
                        ->getNode(),
                    $this->fields,
                ),
            )
            ->getNode();
    }
}
