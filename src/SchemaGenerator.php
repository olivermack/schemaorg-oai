<?php

namespace Olivermack\SchemaorgOpenapi;

use Olivermack\SchemaorgOpenapi\Definition\ClassDefinition;
use Olivermack\SchemaorgOpenapi\Definition\DefinitionCollection;
use Olivermack\SchemaorgOpenapi\Definition\PropertyDefinition;
use Olivermack\SchemaorgOpenapi\Provider\DefinitionsProvider;
use Olivermack\SchemaorgOpenapi\Writer\Writer;

class SchemaGenerator
{
    /** @var DefinitionsProvider */
    private $provider;
    /** @var Writer */
    private $writer;

    public function __construct(DefinitionsProvider $provider, Writer $writer)
    {
        $this->provider = $provider;
        $this->writer = $writer;
    }

    public function generate()
    {
        $properties = $this->provider->getPropertyDefinitions();
        $classes = $this->provider->getClassDefinitions();

        $definitions = $this->combine($classes, $properties);
        $this->writer->write($definitions);
    }

    private function combine(DefinitionCollection $classDefinitions, DefinitionCollection $propertyDefinitions): DefinitionCollection
    {
        /** @var PropertyDefinition $propertyDefinition */
        foreach ($propertyDefinitions as $propertyDefinition) {
            foreach ($propertyDefinition->classes as $className) {
                $classDefinition = $classDefinitions->get($className);

                if ($classDefinition instanceof ClassDefinition) {
                    $classDefinition->addProperty($propertyDefinition);
                }
            }
        }

        return $classDefinitions;
    }
}
