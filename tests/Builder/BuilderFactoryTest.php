<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Tests\Builder;

use Pchapl\CodeGen\Builder\BuilderFactory;
use Pchapl\CodeGen\BuilderFactoryInterface;
use Pchapl\CodeGen\Tests\TestCase;

final class BuilderFactoryTest extends TestCase
{
    private BuilderFactory $builderFactory80;

    protected function setUp(): void
    {
        $this->builderFactory80 = new BuilderFactory();
    }

    public function testDtoBuilder(): void
    {
        self::assertEqualsButNotSame($this->builderFactory80->dtoBuilder(), $this->builderFactory80->dtoBuilder());
    }

    public function testDefaultVersion(): void
    {
        self::assertEqualsButNotSame($this->builderFactory80, new BuilderFactory(BuilderFactoryInterface::VERSION_80));
    }

    public function testSetVersion(): void
    {
        self::assertNotEquals($this->builderFactory80, new BuilderFactory(BuilderFactoryInterface::VERSION_81));
    }
}
