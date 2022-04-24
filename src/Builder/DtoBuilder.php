<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Builder;

use Pchapl\CodeGen\DtoBuilderInterface;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Return_;

final class DtoBuilder implements DtoBuilderInterface
{
    private string $baseNamespace;
    private BuilderFactory $builder;
    private string $name;
    /** @var Field[] $fields */
    private array $fields = [];

    public function __construct(string $baseNamespace = '', ?BuilderFactory $builder = null)
    {
        $this->baseNamespace = $baseNamespace;
        $this->builder = $builder ?? new BuilderFactory();
    }

    public function setName(string $dtoName): self
    {
        $self = clone $this;
        $self->name = $dtoName;
        return $self;
    }

    public function addField(string $fieldName, string $fieldType): DtoBuilderInterface
    {
        $self = clone $this;
        $self->fields[$fieldName] = new Field($fieldName, $fieldType);
        return $self;
    }

    public function build(): Node
    {
        $parts = array_filter(explode('\\', $this->name));

        if (count($parts) > 1) {
            $name = array_pop($parts);
            array_unshift($parts, $this->baseNamespace);
            $namespace = implode('\\', $parts);
        } else {
            $name = $this->name;
            $namespace = $this->baseNamespace;
        }

        $classNode = $this->builder
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

        if ($namespace === '') {
            return $classNode;
        }

        return $this->builder
            ->namespace($namespace)
            ->addStmt($classNode)
            ->getNode();
    }
}
