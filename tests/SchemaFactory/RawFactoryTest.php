<?php

declare(strict_types=1);

namespace Psr\Validator\Tests\SchemaFactory;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Validator\SchemaFactory\RawFactory;
use Psr\Validator\SchemaFactory\SchemaFactoryException;

final class RawFactoryTest extends TestCase
{
    public static function content_provider(): array
    {
        return [
            [
                <<<'HTML'
                <!DOCTYPE html>
                HTML,
            ],
            [
                <<<'JSON'
                <!DOCTYPE html>
                JSON,
            ],
        ];
    }

    #[DataProvider('content_provider')]
    public function test_must_throw_schema_factory_exception(string $content): void
    {
        self::expectException(SchemaFactoryException::class);

        new RawFactory($content);
    }

    public function test_must_return_json_schema(): void
    {
        $schema = (new RawFactory((string) file_get_contents(__DIR__.'/../schemas/schema.json')))->__invoke();

        /* @phpstan-ignore property.notFound */
        self::assertSame('https://json-schema.org/draft/2020-12/schema', $schema->{'$schema'});
    }
}
