<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Builder;

use Pchapl\CodeGen\BuilderFactoryInterface;
use Pchapl\CodeGen\DtoBuilderInterface;
use Pchapl\CodeGen\Exception\VersionException;

final class BuilderFactory implements BuilderFactoryInterface
{
    public function __construct(private string $version = self::VERSION_80)
    {
    }

    public function dtoBuilder(string $baseNamespace = ''): DtoBuilderInterface
    {
        return match ($this->version) {
            self::VERSION_80 => new DtoBuilder80($baseNamespace),
            self::VERSION_81 => new DtoBuilder81($baseNamespace),
            default => throw VersionException::invalidVersion($this->version, [self::VERSION_80, self::VERSION_81]),
        };
    }
}
