<?php

declare(strict_types=1);

namespace Psr\Validator\Schema;

interface SchemaValidatorInterface
{
    public function __invoke(object $data): array;
}
