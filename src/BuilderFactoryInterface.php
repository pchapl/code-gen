<?php

declare(strict_types=1);

namespace Pchapl\CodeGen;

interface BuilderFactoryInterface
{
    public function dto(): DtoBuilderInterface;
}
