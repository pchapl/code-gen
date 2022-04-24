<?php

declare(strict_types=1);

namespace Pchapl\CodeGen;

use PhpParser\Node;

interface EntityInterface
{
    public function getNode(): Node;
}
