<?php

namespace Olivermack\SchemaorgOpenapi\Definition;

class PropertyDefinition extends Definition
{
    /** var array */
    public $classes = [];

    /** var array */
    public $types = [];

    public function addClass(string $class)
    {
        $this->classes[] = $class;
    }

    public function addType(string $range)
    {
        $this->types[] = $range;
        $this->types = array_unique($this->types);
    }

    public function getMostSpecificType()
    {
        return reset($this->types);
    }
}
