<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Tests\Printer;

use Pchapl\CodeGen\Entity\Dto;
use Pchapl\CodeGen\Printer\PrettyPrinter;
use PhpParser\Node\Stmt\Class_;
use PHPUnit\Framework\TestCase;

final class PrettyPrinterTest extends TestCase
{
    private PrettyPrinter $printer;

    protected function setUp(): void
    {
        $this->printer = new PrettyPrinter();
    }

    private const EXPECTED = <<<'PHP'
<?php

declare(strict_types=1);

class tc
{
}

PHP;

    public function testPrint(): void
    {
        $str = $this->printer->print(new Dto(new Class_('tc')));

        self::assertSame(self::EXPECTED, $str);
    }
}
