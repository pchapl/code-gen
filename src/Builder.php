<?php

declare(strict_types=1);

namespace Pchapl\CodeGen;

use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Return_;

final class Builder
{
    private string $baseNamespace;
    private BuilderFactory $builder;

    public function __construct(string $baseNamespace, ?BuilderFactory $builder = null)
    {
        $this->baseNamespace = $baseNamespace;
        $this->builder = $builder ?? new BuilderFactory();
    }

    public function dto(string $name, Field ...$fields): Dto
    {
        return new Dto($name, ...$fields);
    }

    public function field(string $name, string $type): Field
    {
        return new Field($name, $type);
    }

    /**
     * @param Dto $dto
     * @return Node[]
     */
    public function build(Dto $dto): array
    {
        $name = $dto->getName();
        $fields = $dto->getFields();

        $node = $this->builder
            ->namespace($this->baseNamespace)
            ->addStmt(
                $this->builder
                    ->class($name)
                    ->addStmt(
                        $this->builder->method('__construct')
                            ->makePublic()
                            ->addParams(
                                array_map(
                                    static fn (Field $field): Node\Param => new Node\Param(
                                        new Node\Expr\Variable($field->getName()),
                                        null,
                                        $field->getType(),
                                        false,
                                        false,
                                        [],
                                        Class_::MODIFIER_PRIVATE
                                    ),
                                    $fields,
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
                            $fields,
                        ),
                    )
            )
            ->getNode();

        return [$node];
    }
}
