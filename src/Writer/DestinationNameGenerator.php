<?php

namespace Olivermack\SchemaorgOpenapi\Writer;

use Olivermack\SchemaorgOpenapi\Definition\Definition;

interface DestinationNameGenerator
{
    public function relative(Definition $definition): string;
    public function absolute(Definition $definition): string;
}
