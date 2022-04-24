<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Tests;

use Pchapl\CodeGen\Builder\BuilderFactory;
use PhpParser\PrettyPrinter\Standard;
use PHPUnit\Framework\TestCase;

final class DtoBuilderTest extends TestCase
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

    private BuilderFactory $builderFactory;

    protected function setUp(): void
    {
        $this->builderFactory = new BuilderFactory();
    }

    public function testBasicFlow(): void
    {
        $stmt = $this->builderFactory
            ->dto('FooNS')
            ->setName('Foo')
            ->addField('bar', 'string')
            ->build();

        $str = (new Standard())->prettyPrint([$stmt]);

        self::assertSame(self::EXPECTED_DTO, $str);
    }
}
