<?php

namespace ImportCsvBundle\Command;

use ImportCsvBundle\Services\Filters\CostStockFilter;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ParserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:parser')
             ->setDescription('Import data from csv file to database')
             ->addArgument('fileName', InputArgument::REQUIRED, 'file name')
             ->addArgument('testMode', InputArgument::OPTIONAL, 'Enabled test mode.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Import Start');

        $costStockFilter = new CostStockFilter('productCode','cost', 'stock', 5, 1000, 10);
        $result = $this->getContainer()->get('app.parser_csv')
              ->setHelper($costStockFilter)
              ->setFilePath($this->getContainer()->getParameter('csv.file.path').$input->getArgument('fileName'))
              ->parseCSV('ImportCsvBundle:Product', $input->getArgument('testMode'));

        $io->section('Information about import');
        $io->note($input->getArgument('testMode')?'Test mode!':'War mode!');
        if($result) {
            $countSuccess = count($costStockFilter->getSuccessImportMessage());
            $countErrors = count($costStockFilter->getErrorImportMessage());

            $io->table(["All records", 'Success', "Error"],
                [[$countSuccess + $countErrors, $countSuccess, $countErrors]]
            );

            $io->success('Success:');
            $io->text($costStockFilter->getSuccessImportMessage());

            $io->error('Errors:');
            $io->text($costStockFilter->getErrorImportMessage());
        } else {
            $io->text('File not found!');
        }


        $io->title( 'End Import');
    }
}
