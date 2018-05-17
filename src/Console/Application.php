<?php

namespace Olivermack\SchemaorgOpenapi\Console;

use Olivermack\SchemaorgOpenapi\Console\Command\Generate;

class Application extends \Symfony\Component\Console\Application
{
    public function __construct()
    {
        parent::__construct('Olivermack\SchemaorgOpenapi Generator', '1.0.0');

        $this->add(new Generate());
    }

    public function getLongVersion()
    {
        return parent::getLongVersion().' by <comment>Oliver Mack</comment>';
    }
}
