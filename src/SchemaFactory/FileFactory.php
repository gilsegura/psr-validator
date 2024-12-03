<?php

declare(strict_types=1);

namespace Psr\Validator\SchemaFactory;

use Opis\JsonSchema\Resolvers\SchemaResolver;
use Opis\JsonSchema\Uri;
use Psr\Validator\SchemaFactoryInterface;

final readonly class FileFactory implements SchemaFactoryInterface
{
    private string $filename;

    private SchemaResolver $resolver;

    /**
     * @throws SchemaFactoryException
     */
    public function __construct(
        string $filename,
        ?SchemaResolver $resolver = null,
    ) {
        if (false === realpath($filename)) {
            throw SchemaFactoryException::file();
        }

        if ('json' !== pathinfo($filename, PATHINFO_EXTENSION)) {
            throw SchemaFactoryException::file();
        }

        $this->filename = $filename;
        $this->resolver = $resolver ?? new SchemaResolver();
    }

    #[\Override]
    final public function __invoke(): object
    {
        $uri = Uri::create(sprintf('file://%s#', $this->filename));

        if (!$uri instanceof Uri) {
            throw SchemaFactoryException::file();
        }

        $schema = $this->resolver
            ->registerFile($uri->__toString(), $this->filename)
            ->resolve($uri);

        if (!is_object($schema)) {
            throw SchemaFactoryException::notFound();
        }

        return $schema;
    }
}
