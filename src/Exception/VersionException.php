<?php

declare(strict_types=1);

namespace Pchapl\CodeGen\Exception;

use InvalidArgumentException;
use Pchapl\CodeGen\ExceptionInterface;

final class VersionException extends InvalidArgumentException implements ExceptionInterface
{
    /**
     * @param string $version
     * @param string[] $valid
     * @return static
     */
    public static function invalidVersion(string $version, array $valid): self
    {
        return new self(
            sprintf(
                "Invalid version '%s' provided, should be one of %s",
                $version,
                implode(', ', array_map(static fn (string $vv): string => "'$vv'", $valid)),
            )
        );
    }
}
