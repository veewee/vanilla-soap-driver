<?php

declare(strict_types=1);

namespace WsdlTools\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WsdlTools\Filter\Schema\IterableTypeFilter;
use WsdlTools\Formatter\XsdNameFormatter;
use WsdlTools\Formatter\XsdNamespacedNameFormatter;
use WsdlTools\Iterator\FilteredIterator;
use WsdlTools\Iterator\Schema\AllXsdTypesIterator;
use WsdlTools\Model\Schema\XsdType;
use WsdlTools\Wsdl;

class ListIteratorsTypesCommand extends Command
{
    public static function getDefaultName(): string
    {
        return 'list:iterable-types';
    }

    protected function configure(): void
    {
        $this->addArgument('wsdl', InputArgument::REQUIRED, 'Path to wsdl');
        $this->addOption('namespaced', null, InputOption::VALUE_NONE, 'Display the elements namespace');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);
        $wsdl = Wsdl::fromFile($input->getArgument('wsdl'));
        $formatter = $input->getOption('namespaced') ? new XsdNamespacedNameFormatter() : new XsdNameFormatter();
        $count = 0;

        foreach ($this->createIterator($wsdl) as $type) {
            assert($type instanceof XsdType);
            $style->writeln($formatter($type));
            ++$count;
        }

        $style->success("Found {$count} iterable types");

        return 0;
    }

    /**
     * @return iterable<int, XsdType>
     */
    private function createIterator(Wsdl $wsdl): iterable
    {
        return new FilteredIterator(
            new AllXsdTypesIterator($wsdl),
            new IterableTypeFilter($wsdl)
        );
    }
}
