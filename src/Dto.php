<?php

declare(strict_types=1);

namespace Pchapl\CodeGen;

final class Dto
{
    /** @var Field[] */
    private array $fields;

    public function __construct(
        private string $name,
        Field ...$fields,
    ) {
        $this->fields = $fields;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
