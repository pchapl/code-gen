<?php

declare(strict_types=1);

namespace Pchapl\CodeGen;

use PhpParser\Node;

interface BuilderInterface
{
    public function build(): Node;
}
