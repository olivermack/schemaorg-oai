<?php

namespace Olivermack\SchemaorgOpenapi\Writer;

use Olivermack\SchemaorgOpenapi\Definition\Definition;

class FilesystemDestinationNameGenerator implements DestinationNameGenerator
{
    /** @var string */
    private $dir;
    /** @var ?string */
    private $ext;

    public function __construct(string $dir, ?string $ext = null)
    {
        $this->dir = $dir;
        $this->ext = $ext;
    }

    public function relative(Definition $definition): string
    {
        return $definition->name . $this->ext;
    }

    public function absolute(Definition $definition): string
    {
        return $this->dir . DIRECTORY_SEPARATOR . $this->relative($definition);
    }

}
