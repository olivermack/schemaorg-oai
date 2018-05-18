<?php

namespace Olivermack\SchemaorgOpenapi\Writer;

use Olivermack\SchemaorgOpenapi\Definition\ClassDefinition;
use Olivermack\SchemaorgOpenapi\Definition\DefinitionCollection;
use Olivermack\SchemaorgOpenapi\DefinitionSerializer\Serializer;
use Olivermack\SchemaorgOpenapi\Exception\InvalidArgumentException;

class FilesystemWriter implements Writer
{
    /** @var Serializer */
    private $serializer;
    /** @var DestinationNameGenerator */
    private $destinationNameGenerator;

    public function __construct(Serializer $serializer, DestinationNameGenerator $destinationNameGenerator)
    {
        $this->serializer = $serializer;
        $this->destinationNameGenerator = $destinationNameGenerator;
    }

    public function write(DefinitionCollection $definitions): bool
    {
        foreach ($definitions as $definition) {
            if (!$definition instanceof ClassDefinition) {
                throw new InvalidArgumentException(
                    'The ' . __CLASS__ . ' is only writing ClassDefinitions but you passed a Collection containing a ' . get_class($definition)
                );
            }

            $serialized = $this->serializer->serialize($definition, function ($type) use ($definitions) {
                return ($def = $definitions->get($type))
                    ? $this->destinationNameGenerator->relative($def)
                    : null;
            });

            if ($serialized) {
                $dest = $this->destinationNameGenerator->absolute($definition);
                file_put_contents($dest, $serialized);
            }
        }

        return false;
    }
}
