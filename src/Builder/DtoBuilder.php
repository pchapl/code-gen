<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Builder;

use Pchapl\CodeGen\DtoBuilderInterface;
use Pchapl\CodeGen\Entity\Dto;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Return_;

final class DtoBuilder implements DtoBuilderInterface
{
    private const NS_DELIMITER = '\\';

    private string $baseNamespace;
    private BuilderFactory $builder;

    private string $name = '';
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

    public function build(): Dto
    {
        $parts = array_filter(explode(self::NS_DELIMITER, $this->name));
        $name = array_pop($parts) ?: 'Dto';
        array_unshift($parts, $this->baseNamespace);
        $namespace = trim(implode(self::NS_DELIMITER, $parts), self::NS_DELIMITER);

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

        $node = $namespace === ''
            ? $classNode
            : $this->builder
                ->namespace($namespace)
                ->addStmt($classNode)
                ->getNode();

        return new Dto($node);
    }
}
