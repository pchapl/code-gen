<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Tests;

use Pchapl\CodeGen\Builder;
use PhpParser\PrettyPrinter\Standard;
use PHPUnit\Framework\TestCase;

final class BuilderTest extends TestCase
{
    private const EXPECTED_DTO = <<<'PHP'
namespace FooNS;

class Foo
{
    public function __construct(private string $bar)
    {
    }
    public function getBar() : string
    {
        return $this->bar;
    }
}
PHP;

    private Builder $builder;
    private Standard $printer;

    protected function setUp(): void
    {
        $this->builder = new Builder('FooNS');
        $this->printer = new Standard();
    }

    public function testBasicFlow(): void
    {
        self::assertSame(1, 1);

        $dto = $this->builder->dto('Foo', $this->builder->field('bar', 'string'));
        $stmts = $this->builder->build($dto);

        $str = $this->printer->prettyPrint($stmts);

        self::assertSame(self::EXPECTED_DTO, $str);
    }
}
