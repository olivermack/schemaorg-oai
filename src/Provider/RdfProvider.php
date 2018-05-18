<?php

namespace Olivermack\SchemaorgOpenapi\Provider;

use Olivermack\SchemaorgOpenapi\Definition\ClassDefinition;
use Olivermack\SchemaorgOpenapi\Definition\Definition;
use Olivermack\SchemaorgOpenapi\Definition\DefinitionCollection;
use Olivermack\SchemaorgOpenapi\Definition\PropertyDefinition;
use Symfony\Component\DomCrawler\Crawler;

class RdfProvider implements DefinitionsProvider
{
    /** @var string */
    private $sourceUri;
    /** @var Crawler */
    private $crawler;
    /** @var ?callable */
    private $postDefinitionCallback;

    public function __construct(string $sourceUri)
    {
        $this->sourceUri = $sourceUri;
    }

    public function getClassDefinitions(): DefinitionCollection
    {
        $collection = new DefinitionCollection();

        /** @var Crawler[] $matches */
        $matches = $this->find('[typeof="rdfs:Class"]');

        foreach ($matches as $match) {
            $definition = new ClassDefinition();
            $definition->name = $this->text($match, '[property="rdfs:label"]');

            if (in_array($definition->name, ['', 'DataType', 'Float', 'Integer', 'URL'])) {
                continue;
            }

            $definition->parent = $this->text($match, '[property="rdfs:subClassOf"]');

            if (strpos($definition->parent, ':') !== false) {
                continue;
            }

            $this->postDefinitionCreation($definition);
            $collection->addDefinition($definition);
        }

        return $collection;
    }

    public function getPropertyDefinitions(): DefinitionCollection
    {
        $collection = new DefinitionCollection();

        /** @var Crawler[] $matches */
        $matches = $this->find('[typeof="rdf:Property"]');

        foreach ($matches as $match) {
            $definition = new PropertyDefinition();
            $definition->name = $this->text($match, '[property="rdfs:label"]');

            if (empty($definition->name)) {
                continue;
            }

            $match
                ->filter('[property="http://schema.org/domainIncludes"]')
                ->each(function (Crawler $domain) use ($definition) {
                    $definition->addClass($this->text($domain));
                });

            $match
                ->filter('[property="http://schema.org/rangeIncludes"]')
                ->each(function (Crawler $range) use ($definition) {
                    $definition->addType(
                        $this->rangeToType($this->text($range))
                    );
                });

            $this->postDefinitionCreation($definition);
            $collection->addDefinition($definition);
        }

        return $collection;
    }

    public function setPostDefinitionCallback(callable $postDefinitionCallback)
    {
        $this->postDefinitionCallback = $postDefinitionCallback;
    }

    private function postDefinitionCreation(Definition $definition)
    {
        if (is_callable($this->postDefinitionCallback)) {
            call_user_func($this->postDefinitionCallback, $definition);
        }
    }

    private function rangeToType(string $range): string
    {
        switch ($range) {
            case 'Boolean':
            case 'False':
            case 'True':
                return 'boolean';
            case 'Date':
                return 'date';
            case 'Time':
            case 'DateTime':
                return 'dateTime';
            case 'Text':
            case 'URL':
                return 'string';
            case 'Number':
            case 'Integer':
                return 'integer';
            case 'Float':
                return 'float';
            case 'Double':
                return 'double';
            default:
                return '$ref:' . $range;
        }
    }

    private function find($filter): array
    {
        $crawler = $this->crawler ?? $this->load();

        return $crawler->filter($filter)->each(function (Crawler $crawler) {
            return $crawler;
        });
    }

    private function load(): Crawler
    {
        $this->crawler = new Crawler(file_get_contents($this->sourceUri), $this->sourceUri);

        return $this->crawler;
    }

    private function text(Crawler $node, string $selector = null, $default = null): ?string
    {
        $node = !empty($selector) ? $node->filter($selector) : $node;

        return $node->count()
            ? $node->first()->text()
            : $default;
    }

    protected function attribute(Crawler $node, string $attribute, $default = null): string
    {
        if ($node->count() === 0) {
            return $default;
        }

        return $node->filter("[{$attribute}]")->attr($attribute) ?? $default;
    }
}
