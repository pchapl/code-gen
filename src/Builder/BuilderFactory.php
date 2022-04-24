<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Builder;

use Pchapl\CodeGen\BuilderFactoryInterface;
use Pchapl\CodeGen\DtoBuilderInterface;

final class BuilderFactory implements BuilderFactoryInterface
{
    public function __construct(private ?\PhpParser\BuilderFactory $builderFactory = null)
    {
    }

    public function dto(string $baseNamespace = ''): DtoBuilderInterface
    {
        return new DtoBuilder($baseNamespace, $this->builderFactory);
    }
}
