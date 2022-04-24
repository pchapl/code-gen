<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Tests\Builder;

use Pchapl\CodeGen\Builder\BuilderFactory;
use Pchapl\CodeGen\Builder\DtoBuilder;
use Pchapl\CodeGen\Tests\TestCase;
use PhpParser\PrettyPrinter\Standard;

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

    public function testNamespacedName(): void
    {
        $str1 = (new Standard())->prettyPrint([$this->builderFactory->dto()->setName('\Foo\Bar')->build()]);
        $str2 = (new Standard())->prettyPrint([$this->builderFactory->dto('Foo')->setName('\Bar')->build()]);
        $str3 = (new Standard())->prettyPrint([$this->builderFactory->dto('Foo')->setName('\Bar\\')->build()]);
        $str4 = (new Standard())->prettyPrint([$this->builderFactory->dto('\\\\')->setName('\\Foo\\Bar\\')->build()]);

        self::assertAllSame($str1, $str2, $str3, $str4);
    }

    public function testDefaultName(): void
    {
        $str = (new Standard())->prettyPrint([$this->builderFactory->dto()->setName('')->build()]);

        self::assertStringStartsWith("class Dto\n", $str);
    }

    public function testConstruct(): void
    {
        $factory = new \PhpParser\BuilderFactory();
        $baseNamespace = 'basic-namespace-test';

        $dtoBuilderWithFactory = new DtoBuilder($baseNamespace, $factory);

        self::assertSame($baseNamespace, $this->getPrivateProperty($dtoBuilderWithFactory, 'baseNamespace'));
        self::assertSame($factory, $this->getPrivateProperty($dtoBuilderWithFactory, 'builder'));

        $dtoBuilder = new DtoBuilder();

        self::assertSame('', $this->getPrivateProperty($dtoBuilder, 'baseNamespace'));
        self::assertNotSame($factory, $this->getPrivateProperty($dtoBuilder, 'builder'));
        self::assertEquals($factory, $this->getPrivateProperty($dtoBuilder, 'builder'));
    }

    public function testSetName(): void
    {
        $dtoBuilder = $this->builderFactory->dto();

        $dtoName = 'test-name';

        $dtoBuilder1 = $dtoBuilder->setName($dtoName);

        self::assertNotSame($dtoBuilder, $dtoBuilder1);

        $dtoBuilder2 = $dtoBuilder->setName($dtoName);

        self::assertEqualsButNotSame($dtoBuilder1, $dtoBuilder2);
    }

    public function testAddField(): void
    {
        $dtoBuilder = $this->builderFactory->dto();

        $fieldName = 'test-field-name';
        $fieldType = 'test-field-type';

        $dtoBuilder1 = $dtoBuilder->addField($fieldName, $fieldType);

        self::assertNotSame($dtoBuilder, $dtoBuilder1);

        $dtoBuilder2 = $dtoBuilder->addField($fieldName, $fieldType);

        self::assertEqualsButNotSame($dtoBuilder1, $dtoBuilder2);

        $dtoBuilder3 = $dtoBuilder2->addField($fieldName, $fieldType);

        self::assertEqualsButNotSame($dtoBuilder2, $dtoBuilder3);
    }
}
