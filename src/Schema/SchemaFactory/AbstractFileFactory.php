<?php

declare(strict_types=1);

namespace Psr\Validator\Schema\SchemaFactory;

use ProxyAssert\Assertion;
use Psr\Validator\SchemaFactoryInterface;

abstract readonly class AbstractFileFactory implements SchemaFactoryInterface
{
    protected string $filename;

    public function __construct(
        string $filename,
    ) {
        Assertion::file($filename);

        $this->filename = $filename;
    }
}
