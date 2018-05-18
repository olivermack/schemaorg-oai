<?php

namespace Olivermack\SchemaorgOpenapi\Console\Command;

use Olivermack\SchemaorgOpenapi\Provider\RdfProvider;
use Olivermack\SchemaorgOpenapi\SchemaGenerator;
use Olivermack\SchemaorgOpenapi\DefinitionSerializer\JsonSerializer;
use Olivermack\SchemaorgOpenapi\Writer\DestinationBasedReferenceLocator;
use Olivermack\SchemaorgOpenapi\Writer\FilesystemDestinationNameGenerator;
use Olivermack\SchemaorgOpenapi\Writer\FilesystemWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Generate extends Command
{
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('Generate the json files')
            ->addOption('local', 'l', InputOption::VALUE_NONE, 'Use a cached version of the source');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);
        $style->progressStart();

        $provider = new RdfProvider('https://raw.githubusercontent.com/schemaorg/schemaorg/sdo-callisto/data/schema.rdfa');
        $provider->setPostDefinitionCallback(function () use ($style) {
            $style->progressAdvance();
        });

        $dir = __DIR__ . '/../../../schemas';
        $fileExtension = '.json';
        $destinationNameGenerator = new FilesystemDestinationNameGenerator($dir, $fileExtension);
        $serializer = new JsonSerializer(new DestinationBasedReferenceLocator($destinationNameGenerator));
        $writer = new FilesystemWriter($serializer, $destinationNameGenerator);
        $generator = new SchemaGenerator($provider, $writer);

        $generator->generate();


        $output->writeln('Done!');
    }
}
