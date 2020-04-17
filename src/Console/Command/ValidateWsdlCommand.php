<?php

declare(strict_types=1);

namespace WsdlTools\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use WsdlTools\Validator\Chain;
use WsdlTools\Validator\Document\XsdValidator;
use WsdlTools\Validator\SchemaXsdValidator;
use WsdlTools\Validator\ValidatorInterface;
use WsdlTools\Validator\WsdlXsdValidator;
use WsdlTools\Wsdl;

class ValidateWsdlCommand extends Command
{
    private ValidatorInterface $validator;

    public static function getDefaultName()
    {
        return 'validate';
    }

    public function __construct()
    {
        parent::__construct();

        $xsdValidator = new XsdValidator(new Filesystem(), dirname(__DIR__, 3).DIRECTORY_SEPARATOR.'validators');
        $this->validator = new Chain(
            new WsdlXsdValidator($xsdValidator),
            new SchemaXsdValidator($xsdValidator)
        );
    }

    protected function configure()
    {
        $this->setDescription('Validate a WSDL');
        $this->addArgument('wsdl', InputArgument::REQUIRED, 'Path to wsdl');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);
        $wsdl = Wsdl::fromFile($input->getArgument('wsdl'));

        $count = 0;
        foreach ($this->validator->validate($wsdl) as $error) {
            ++$count;
            $style->writeln('<fg=red>'.$error.'</fg=red>');
        }

        if ($count > 0) {
            $style->error("Found {$count} validation errors");
            return 1;
        }

        $style->success('Found no errors.');
        return 0;
    }
}
