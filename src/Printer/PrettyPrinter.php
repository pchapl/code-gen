<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Printer;

use Pchapl\CodeGen\EntityInterface;
use Pchapl\CodeGen\PrettyPrinterInterface;
use PhpParser\PrettyPrinter\Standard;

final class PrettyPrinter implements PrettyPrinterInterface
{
    private Standard $standardPrinter;

    public function __construct()
    {
        $this->standardPrinter = new Extended();
    }

    public function print(EntityInterface $entity): string
    {
        $print = $this->standardPrinter->prettyPrint([$entity->getNode()]);

        return "<?php\n\ndeclare(strict_types=1);\n\n" . $print;
    }
}
