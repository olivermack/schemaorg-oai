<?php

namespace Olivermack\SchemaorgOpenapi\DefinitionSerializer;

use Olivermack\SchemaorgOpenapi\Definition\Definition;

interface Serializer
{
    public function serialize(Definition $definition, callable $referenceCallback = null): ?string;
}
