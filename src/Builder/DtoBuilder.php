<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Builder;

use Pchapl\CodeGen\DtoBuilderInterface;
use Pchapl\CodeGen\Entity\Dto;
use PhpParser\BuilderFactory;
use PhpParser\Node\Stmt\Class_;

abstract class DtoBuilder implements DtoBuilderInterface
{
    protected const NS_DELIMITER = '\\';

    protected string $baseNamespace;
    protected BuilderFactory $builder;

    protected string $name = '';
    /** @var Field[] $fields */
    protected array $fields = [];

    final public function __construct(string $baseNamespace = '')
    {
        $this->baseNamespace = $baseNamespace;
        $this->builder = new BuilderFactory();
    }

    final public function setName(string $dtoName): static
    {
        $self = clone $this;
        $self->name = $dtoName;
        return $self;
    }

    final public function addField(string $fieldName, string $fieldType): static
    {
        $self = clone $this;
        $self->fields[$fieldName] = new Field($fieldName, $fieldType);
        return $self;
    }

    final public function build(): Dto
    {
        $parts = array_filter(explode(self::NS_DELIMITER, $this->name));
        $name = array_pop($parts) ?: 'Dto';
        array_unshift($parts, $this->baseNamespace);
        $namespace = trim(implode(self::NS_DELIMITER, $parts), self::NS_DELIMITER);

        $classNode = $this->buildClass($name);

        $node = $namespace === ''
            ? $classNode
            : $this->builder
                ->namespace($namespace)
                ->addStmt($classNode)
                ->getNode();

        return new Dto($node);
    }

    abstract protected function buildClass(string $name): Class_;
}
