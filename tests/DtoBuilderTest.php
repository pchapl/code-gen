<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Tests;

use Pchapl\CodeGen\Builder\BuilderFactory;
use PhpParser\PrettyPrinter\Standard;
use PHPUnit\Framework\TestCase;

final class DtoBuilderTest extends TestCase
{
    private BuilderFactory $builderFactory;

    protected function setUp(): void
    {
        $this->builderFactory = new BuilderFactory();
    }

    private const TEST_BASIC_FLOW_EXPECTED_DTO = <<<'PHP'
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

    public function testBasicFlow(): void
    {
        $stmt = $this->builderFactory
            ->dto('FooNS')
            ->setName('Foo')
            ->addField('bar', 'string')
            ->build();

        $str = (new Standard())->prettyPrint([$stmt]);

        self::assertSame(self::TEST_BASIC_FLOW_EXPECTED_DTO, $str);
    }

    private const TEST_NAMESPACE_EXPECTED_DTO = <<<'PHP'
namespace FooNS\Bar;

class Foo
{
    public function __construct()
    {
    }
}
PHP;

    public function testNamespace(): void
    {
        $stmt = $this->builderFactory
            ->dto('FooNS')
            ->setName('Bar\Foo')
            ->build();

        $str = (new Standard())->prettyPrint([$stmt]);

        self::assertSame(self::TEST_NAMESPACE_EXPECTED_DTO, $str);
    }

    private const TEST_NO_NAMESPACE_EXPECTED_DTO = <<<'PHP'
class FooBar
{
    public function __construct()
    {
    }
}
PHP;

    public function testNoNamespace(): void
    {
        $stmt = $this->builderFactory
            ->dto()
            ->setName('FooBar')
            ->build();

        $str = (new Standard())->prettyPrint([$stmt]);

        self::assertSame(self::TEST_NO_NAMESPACE_EXPECTED_DTO, $str);
    }
}
