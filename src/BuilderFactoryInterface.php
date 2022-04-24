<?php

declare(strict_types=1);

namespace Pchapl\CodeGen;

interface BuilderFactoryInterface
{
    public const VERSION_80 = '8.0';
    public const VERSION_81 = '8.1';

    public function dtoBuilder(): DtoBuilderInterface;
}
