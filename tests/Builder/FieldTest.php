<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Tests\Builder;

use Pchapl\CodeGen\Builder\Field;
use Pchapl\CodeGen\Tests\TestCase;

final class FieldTest extends TestCase
{
    private const NAME = 'test-field-name';
    private const TYPE = 'test-field-type';

    public function testField(): void
    {
        $field = new Field(self::NAME, self::TYPE);

        self::assertSame(self::NAME, $field->getName());
        self::assertSame(self::TYPE, $field->getType());
    }
}
