<?php

declare(strict_types=1);

namespace Psr\Validator;

use Psr\Validator\SchemaFactory\SchemaFactoryException;

interface SchemaFactoryInterface
{
    /**
     * @throws SchemaFactoryException
     */
    public function __invoke(): object;
}
