<?php

declare(strict_types=1);

namespace Psr\Validator\Schema;

use JsonSchema\Validator;

final readonly class Schema implements SchemaValidatorInterface
{
    public function __construct(
        public object $schema,
    ) {
    }

    #[\Override]
    public function __invoke(object $data): array
    {
        $validator = new Validator();

        $validator->validate($data, $this->schema);

        return $validator->getErrors();
    }
}
