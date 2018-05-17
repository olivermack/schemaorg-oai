<?php

namespace Olivermack\SchemaorgOpenapi\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Generate extends Command
{
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('Generate the json files')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
