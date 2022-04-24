<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Tests\Builder;

use Pchapl\CodeGen\Builder\BuilderFactory;
use Pchapl\CodeGen\Tests\TestCase;

final class BuilderFactoryTest extends TestCase
{
    private BuilderFactory $builderFactory;

    protected function setUp(): void
    {
        $this->builderFactory = new BuilderFactory();
    }

    public function testDto(): void
    {
        self::assertEqualsButNotSame($this->builderFactory->dto(), $this->builderFactory->dto());
    }
}
