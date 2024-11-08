<?php

declare(strict_types=1);

namespace Psr\Validator;

interface SchemaFactoryInterface
{
    public function __invoke(): object;
}
