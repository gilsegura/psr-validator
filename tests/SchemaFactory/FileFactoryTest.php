<?php

declare(strict_types=1);

namespace Psr\Validator\Tests\SchemaFactory;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Validator\SchemaFactory\FileFactory;
use Psr\Validator\SchemaFactory\SchemaFactoryException;

final class FileFactoryTest extends TestCase
{
    public static function filename_provider(): array
    {
        return [
            ['/not/existing/path'],
            [__DIR__.'/../schemas/schema.txt'],
        ];
    }

    #[DataProvider('filename_provider')]
    public function test_must_throw_schema_factory_exception(string $filename): void
    {
        self::expectException(SchemaFactoryException::class);

        new FileFactory($filename);
    }

    public function test_must_return_json_schema(): void
    {
        $schema = (new FileFactory(__DIR__.'/../schemas/schema.json'))->__invoke();

        /* @phpstan-ignore property.notFound */
        self::assertSame('https://json-schema.org/draft/2020-12/schema', $schema->{'$schema'});
    }
}
