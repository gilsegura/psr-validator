<?php

declare(strict_types=1);

namespace Psr\Validator\Schema;

use Psr\Validator\Exception\ValidationExceptionInterface;

final class ValidatorException extends \Exception implements ValidationExceptionInterface
{
    public static function throwable(\Throwable $e): self
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }
}
