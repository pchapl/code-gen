<?php

declare(strict_types=1);

namespace Pchapl\CodeGen;

use Pchapl\CodeGen\Entity\Dto;

interface DtoBuilderInterface extends BuilderInterface
{
    public function build(): Dto;

    public function setName(string $dtoName): self;

    public function addField(string $fieldName, string $fieldType): self;
}
