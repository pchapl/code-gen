<?php

declare(strict_types=1);

namespace Pchapl\CodeGen;

interface DtoBuilderInterface extends BuilderInterface
{
    public function setName(string $dtoName): self;

    public function addField(string $fieldName, string $fieldType): self;
}
