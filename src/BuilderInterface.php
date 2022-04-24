<?php

declare(strict_types=1);

namespace Pchapl\CodeGen;

interface BuilderInterface
{
    public function build(): EntityInterface;
}
