<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Tests\Builder;

use Pchapl\CodeGen\Builder\BuilderFactory;
use Pchapl\CodeGen\Builder\DtoBuilder80;
use Pchapl\CodeGen\Tests\TestCase;
use PhpParser\PrettyPrinter\Standard;

final class DtoBuilder80Test extends TestCase
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
            ->dtoBuilder('FooNS')
            ->setName('Foo')
            ->addField('bar', 'string')
            ->build();

        $str = (new Standard())->prettyPrint([$stmt->getNode()]);

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
            ->dtoBuilder('FooNS')
            ->setName('Bar\Foo')
            ->build();

        $str = (new Standard())->prettyPrint([$stmt->getNode()]);

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
            ->dtoBuilder()
            ->setName('FooBar')
            ->build();

        $str = (new Standard())->prettyPrint([$stmt->getNode()]);

        self::assertSame(self::TEST_NO_NAMESPACE_EXPECTED_DTO, $str);
    }

    public function testNamespacedName(): void
    {
        $pr = new Standard();
        $str1 = $pr->prettyPrint([$this->builderFactory->dtoBuilder()->setName('\Foo\Bar')->build()->getNode()]);
        $str2 = $pr->prettyPrint([$this->builderFactory->dtoBuilder('Foo')->setName('\Bar')->build()->getNode()]);
        $str3 = $pr->prettyPrint([$this->builderFactory->dtoBuilder('Foo')->setName('\Bar\\')->build()->getNode()]);
        $str4 = $pr->prettyPrint(
            [$this->builderFactory->dtoBuilder('\\\\')->setName('\\Foo\\Bar\\')->build()->getNode()]
        );

        self::assertAllSame($str1, $str2, $str3, $str4);
    }

    public function testDefaultName(): void
    {
        $str = (new Standard())->prettyPrint([$this->builderFactory->dtoBuilder()->setName('')->build()->getNode()]);

        self::assertStringStartsWith("class Dto\n", $str);
    }

    public function testConstruct(): void
    {
        $baseNamespace = 'basic-namespace-test';

        $dtoBuilderWithFactory = new DtoBuilder80($baseNamespace);

        self::assertSame($baseNamespace, $this->getPrivateProperty($dtoBuilderWithFactory, 'baseNamespace'));

        $dtoBuilder = new DtoBuilder80();

        self::assertSame('', $this->getPrivateProperty($dtoBuilder, 'baseNamespace'));
    }

    public function testSetName(): void
    {
        $dtoBuilder = $this->builderFactory->dtoBuilder();

        $dtoName = 'test-name';

        $dtoBuilder1 = $dtoBuilder->setName($dtoName);

        self::assertNotSame($dtoBuilder, $dtoBuilder1);

        $dtoBuilder2 = $dtoBuilder->setName($dtoName);

        self::assertEqualsButNotSame($dtoBuilder1, $dtoBuilder2);
    }

    public function testAddField(): void
    {
        $dtoBuilder = $this->builderFactory->dtoBuilder();

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
