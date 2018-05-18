<?php

namespace Olivermack\SchemaorgOpenapi\Definition;

class ClassDefinition extends Definition
{
    /** @var DefinitionCollection */
    public $properties;

    public function __construct()
    {
        $this->properties = new DefinitionCollection();
    }

    public function addProperty(PropertyDefinition $property)
    {
        $this->properties->addDefinition($property);
    }
}
