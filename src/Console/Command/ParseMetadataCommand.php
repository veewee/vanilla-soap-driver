<?php

declare(strict_types=1);

namespace WsdlTools\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WsdlTools\Metadata\WsdlMetadataProvider;
use WsdlTools\Wsdl;

class ParseMetadataCommand extends Command
{
    public static function getDefaultName()
    {
        return 'parse:meta';
    }

    protected function configure()
    {
        $this->addArgument('wsdl', InputArgument::REQUIRED, 'Path to wsdl');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);
        $wsdl = Wsdl::fromFile($input->getArgument('wsdl'));


        $metadata = new WsdlMetadataProvider($wsdl);

        dump($metadata->getMethods());

        return 0;
    }
}
