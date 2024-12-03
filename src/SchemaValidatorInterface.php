<?php

declare(strict_types=1);

namespace Psr\Validator;

use Psr\Validator\Schema\ValidatorException;

interface SchemaValidatorInterface
{
    /**
     * @throws ValidatorException
     */
    public function __invoke(object $data, object $schema): array;
}
