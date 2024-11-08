<?php

declare(strict_types=1);

namespace Psr\Validator\Schema\SchemaFactory;

use JsonSchema\SchemaStorage;

final readonly class JsonFileFactory extends AbstractFileFactory
{
    #[\Override]
    public function __invoke(): object
    {
        $storage = new SchemaStorage();

        return $storage->getSchema(sprintf('file://%s', realpath($this->filename)));
    }
}
