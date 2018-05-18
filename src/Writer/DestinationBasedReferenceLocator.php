<?php

namespace Olivermack\SchemaorgOpenapi\Writer;

use Olivermack\SchemaorgOpenapi\Definition\Definition;

class DestinationBasedReferenceLocator implements ReferenceLocator
{
    /** @var DestinationNameGenerator */
    private $destinationNameGenerator;

    public function __construct(DestinationNameGenerator $destinationNameGenerator)
    {
        $this->destinationNameGenerator = $destinationNameGenerator;
    }

    public function getRef(Definition $definition): string
    {
        return $this->destinationNameGenerator->relative($definition);
    }
}
