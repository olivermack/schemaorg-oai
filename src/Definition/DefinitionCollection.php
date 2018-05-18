<?php

namespace Olivermack\SchemaorgOpenapi\Definition;

class DefinitionCollection extends \ArrayObject
{
    public function addDefinition(Definition $definition)
    {
        $this->offsetSet($definition->name, $definition);
    }

    public function getNames(): array
    {
        return array_map(function (Definition $definition) {
            return $definition->name;
        }, $this->getArrayCopy());
    }

    public function get($key, $default = null): ?Definition
    {
        return $this->offsetExists($key) ? $this->offsetGet($key) : $default;
    }
}
