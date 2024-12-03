<?php

declare(strict_types=1);

namespace Psr\Validator\SchemaFactory;

use Opis\JsonSchema\Resolvers\SchemaResolver;
use Opis\JsonSchema\Uri;
use Psr\Validator\SchemaFactoryInterface;

final readonly class RawFactory implements SchemaFactoryInterface
{
    private object $content;

    private SchemaResolver $resolver;

    /**
     * @throws SchemaFactoryException
     */
    public function __construct(
        string $content,
        ?SchemaResolver $resolver = null,
    ) {
        if (!json_validate($content)) {
            throw SchemaFactoryException::content();
        }

        try {
            $this->content = (object) json_decode($content, false, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw SchemaFactoryException::throwable($e);
        }

        $this->resolver = $resolver ?? new SchemaResolver();
    }

    #[\Override]
    final public function __invoke(): object
    {
        $uri = Uri::create(sprintf('schema:///%s.json#', spl_object_hash($this->content)));

        if (!$uri instanceof Uri) {
            throw SchemaFactoryException::content();
        }

        $this->resolver->registerRaw($this->content, $uri->__toString());

        $schema = $this->resolver->resolve($uri);

        if (!is_object($schema)) {
            throw SchemaFactoryException::notFound();
        }

        return $schema;
    }
}
