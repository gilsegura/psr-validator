<?php

declare(strict_types=1);

namespace Psr\Validator\Tests\Schema;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Validator\Schema\SchemaValidator;
use Psr\Validator\SchemaFactory\FileFactory;
use Psr\Validator\SchemaFactory\RawFactory;

final class SchemaValidatorTest extends TestCase
{
    public static function schema_provider(): array
    {
        return [
            [
                (new FileFactory(__DIR__.'/../schemas/schema.json'))->__invoke(),
            ],
            [
                (new RawFactory((string) file_get_contents(__DIR__.'/../schemas/schema.json')))->__invoke(),
            ],
        ];
    }

    #[DataProvider('schema_provider')]
    public function test_must_validate_having_errors(object $schema): void
    {
        $errors = (new SchemaValidator())->__invoke((object) ['foo' => 'bar'], $schema);

        self::assertNotEmpty($errors);
    }
}
