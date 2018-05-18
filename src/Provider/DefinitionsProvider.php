<?php

namespace Olivermack\SchemaorgOpenapi\Provider;

use Olivermack\SchemaorgOpenapi\Definition\DefinitionCollection;

interface DefinitionsProvider
{
    public function getClassDefinitions(): DefinitionCollection;
    public function getPropertyDefinitions(): DefinitionCollection;
}
