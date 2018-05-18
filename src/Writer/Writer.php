<?php

namespace Olivermack\SchemaorgOpenapi\Writer;

use Olivermack\SchemaorgOpenapi\Definition\DefinitionCollection;

interface Writer
{
    public function write(DefinitionCollection $definitions): bool;
}

