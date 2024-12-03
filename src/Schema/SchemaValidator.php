<?php

declare(strict_types=1);

namespace Psr\Validator\Schema;

use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Errors\ValidationError;
use Opis\JsonSchema\JsonPointer;
use Opis\JsonSchema\Parsers\SchemaParser;
use Opis\JsonSchema\Resolvers\SchemaResolver;
use Opis\JsonSchema\SchemaLoader;
use Opis\JsonSchema\Validator;
use Psr\Validator\SchemaValidatorInterface;

final readonly class SchemaValidator implements SchemaValidatorInterface
{
    private Validator $validator;

    public function __construct(
        ?SchemaResolver $resolver = null,
    ) {
        $this->validator = new Validator(
            new SchemaLoader(
                new SchemaParser(),
                $resolver ?? new SchemaResolver()
            )
        );
    }

    #[\Override]
    public function __invoke(object $data, object $schema): array
    {
        try {
            $validation = $this->validator->validate(clone $data, clone $schema)
                ->error();

            if (!$validation instanceof ValidationError) {
                return [];
            }

            $formatter = new ErrorFormatter();

            return $formatter->formatFlat(
                $validation,
                static fn (ValidationError $error): array => [
                    'property' => JsonPointer::pathToString($error->data()->fullPath()),
                    'pointer' => JsonPointer::pathToFragment($error->data()->fullPath()),
                    'message' => $formatter->formatErrorMessage($error),
                    'constraint' => [
                        'keyword' => $error->keyword(),
                        'args' => $error->args(),
                    ],
                    'context' => [
                        'type' => $error->data()->type(),
                        'value' => $error->data()->value(),
                        'path' => $error->data()->fullPath(),
                    ],
                ]
            );
        } catch (\Throwable $e) {
            throw ValidatorException::throwable($e);
        }
    }
}
