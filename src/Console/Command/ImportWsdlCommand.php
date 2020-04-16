<?php

declare(strict_types=1);

namespace WsdlTools\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportWsdlCommand extends Command
{
    public static function getDefaultName()
    {
        return 'import';
    }

    protected function configure()
    {
        $this->setDescription('Moves all external items inline');
        $this->addArgument('wsdl', InputArgument::REQUIRED, 'Path to wsdl');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("not implemented yet...;");
        return 1;
    }
}
