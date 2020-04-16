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
use WsdlTools\Iterator\Schema\GroupByNameIterator;
use WsdlTools\Model\Schema\XsdType;
use WsdlTools\Wsdl;

class ListDuplicateTypesCommand extends Command
{
    public static function getDefaultName()
    {
        return 'list:duplicate-types';
    }

    protected function configure(): void
    {
        $this->addArgument('wsdl', InputArgument::REQUIRED, 'Path to wsdl');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);
        $wsdl = Wsdl::fromFile($input->getArgument('wsdl'));
        $formatter = new XsdNamespacedNameFormatter();
        $count = 0;


        //var_dump([...($this->createIterator($wsdl))]);exit;

        foreach ($this->createIterator($wsdl) as $name => $types) {
            $style->section($name);
            $style->listing(array_map(
                fn (XsdType $type) => $formatter($type),
                $types
            ));

            ++$count;
        }

        $style->success("Found {$count} duplicate types");

        return 0;
    }

    /**
     * @return iterable<int, XsdType>
     */
    private function createIterator(Wsdl $wsdl): iterable
    {
        return new FilteredIterator(
            new GroupByNameIterator(
                new FilteredIterator(
                    new AllXsdTypesIterator($wsdl),
                    fn (XsdType $type): bool => !$type->isElement()
                )
            ),
            fn (array $grouped) => count($grouped) > 1
        );
    }
}
