<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Tests\Builder;

use Pchapl\CodeGen\Builder\BuilderFactory;
use Pchapl\CodeGen\BuilderFactoryInterface;
use Pchapl\CodeGen\Tests\TestCase;
use PhpParser\PrettyPrinter\Standard;

final class DtoBuilder81Test extends TestCase
{
    private BuilderFactory $builderFactory;

    protected function setUp(): void
    {
        $this->builderFactory = new BuilderFactory(BuilderFactoryInterface::VERSION_81);
    }

    private const TEST_BASIC_FLOW_EXPECTED_DTO = <<<'PHP'
namespace FooNS;

class Foo
{
    public function __construct(public readonly string $bar)
    {
    }
}
PHP;

    public function testBasicFlow(): void
    {
        $stmt = $this->builderFactory
            ->dtoBuilder('FooNS')
            ->setName('Foo')
            ->addField('bar', 'string')
            ->build();

        $str = (new Standard())->prettyPrint([$stmt->getNode()]);

        self::assertSame(self::TEST_BASIC_FLOW_EXPECTED_DTO, $str);
    }
}
