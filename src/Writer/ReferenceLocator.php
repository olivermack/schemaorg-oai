<?php

namespace Olivermack\SchemaorgOpenapi\Writer;

use Olivermack\SchemaorgOpenapi\Definition\Definition;

interface ReferenceLocator
{
    public function getRef(Definition $definition): string;
}
