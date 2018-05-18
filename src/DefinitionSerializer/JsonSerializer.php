<?php

namespace Olivermack\SchemaorgOpenapi\DefinitionSerializer;

use Olivermack\SchemaorgOpenapi\Definition\ClassDefinition;
use Olivermack\SchemaorgOpenapi\Definition\Definition;
use Olivermack\SchemaorgOpenapi\Definition\DefinitionCollection;
use Olivermack\SchemaorgOpenapi\Definition\PropertyDefinition;
use Olivermack\SchemaorgOpenapi\Writer\ReferenceLocator;

class JsonSerializer implements Serializer
{
    const REF = '$ref';
    const REF_PREFIX = self::REF . ':';

    /** @var ReferenceLocator */
    private $referenceLocator;

    public function __construct(ReferenceLocator $referenceLocator)
    {
        $this->referenceLocator = $referenceLocator;
    }

    public function serialize(Definition $definition, callable $referenceCallback = null): ?string
    {
        if ($definition instanceof ClassDefinition) {
            return json_encode($this->serializeClass($definition, $referenceCallback), JSON_PRETTY_PRINT);
        }

        if ($definition instanceof PropertyDefinition) {
            return json_encode($this->serializeProperty($definition, $referenceCallback), JSON_PRETTY_PRINT);
        }

        return null;
    }

    private function serializeClass(ClassDefinition $definition, callable $referenceCallback = null)
    {
        $schema = [
            $definition->name => [
                'type' => 'object',
                'properties' => $this->serializeProperties($definition->properties, $referenceCallback),
            ],
        ];

        return $schema;
    }

    private function serializeProperties(DefinitionCollection $definitions, callable $referenceCallback = null)
    {
        $schema = [];

        /** @var PropertyDefinition $definition */
        foreach ($definitions as $definition) {
            $schema[$definition->name] = $this->serializeProperty($definition, $referenceCallback);
        }

        return $schema;
    }

    private function serializeProperty(PropertyDefinition $definition, callable $referenceCallback = null)
    {
        return array_merge([
            'description' => $definition->description,
        ], $this->getTypeSpecs($definition, $referenceCallback));
    }

    private function getTypeSpecs(PropertyDefinition $definition, callable $referenceCallback = null)
    {
        $type = $definition->getMostSpecificType();
        $specs = [
            'type' => $type,
        ];


        switch ($type) {
            case 'date':
                $specs = [
                    'type' => 'string',
                    'format' => 'date',
                ];
                break;

            case 'dateTime':
                $specs = [
                    'type' => 'string',
                    'format' => 'date-time',
                ];
                break;

            case 'time':
                $specs = [
                    'type' => 'string',
                    'format' => 'date',
                ];
                break;

            case 'double':
                $specs = [
                    'type' => 'number',
                    'format' => 'double',
                ];
                break;

            case 'float':
                $specs = [
                    'type' => 'number',
                    'format' => 'float',
                ];
                break;

            case 'int':
            case 'integer':
                $specs = [
                    'type' => 'integer',
                    'format' => 'int32',
                ];
                break;

            case 'long':
            case 'bigint':
                $specs = [
                    'type' => 'integer',
                    'format' => 'int64',
                ];
                break;
        }

        if (strpos($type, self::REF) !== false && ($ref = $this->getRef($type, $referenceCallback))) {
            $specs = [
                self::REF => $ref,
            ];
        }

        return $specs;
    }

    private function getRef($type, callable $referenceCallback = null): ?string
    {
        return is_callable($referenceCallback)
            ? $referenceCallback(str_replace(self::REF_PREFIX, '', $type))
            : null;
    }
}
