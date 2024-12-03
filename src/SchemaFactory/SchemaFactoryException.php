<?php

declare(strict_types=1);

namespace Psr\Validator\SchemaFactory;

use Psr\Validator\Exception\ValidationExceptionInterface;

final class SchemaFactoryException extends \Exception implements ValidationExceptionInterface
{
    public static function schema(): self
    {
        return new self('The requested schema does not valid.', 409);
    }

    public static function notFound(): self
    {
        return new self('The requested schema could not be found.', 404);
    }

    public static function content(): self
    {
        return new self('The provided content does not valid.', 400);
    }

    public static function file(): self
    {
        return new self('The provided file does not valid.', 400);
    }

    public static function throwable(\Throwable $e): self
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }
}
