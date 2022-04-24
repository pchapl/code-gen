<?php

declare(strict_types=1);

namespace Pchapl\CodeGen;

interface PrettyPrinterInterface
{
    public function print(EntityInterface $entity): string;
}
