<?php

namespace ImportCsvBundle\Services;

use ImportCsvBundle\Services\Writer\EmptyWriter;
use Doctrine\ORM\EntityManager;
use Port\Csv\CsvReader;
use Port\Steps\StepAggregator;
use Port\Steps\Step\MappingStep;
use Port\Steps\Step\ConverterStep;
use Port\Steps\Step\FilterStep;
use Port\Doctrine\DoctrineWriter;
use ImportCsvBundle\Services\Filters\CostStockFilter;
use SplFileInfo;

/**
 * class ParserCsvService
 *
 * This service is used to import data from a csv file into a database.
 **/
class ParserCsvService
{

    /**
     * Doctrine’s EntityManager
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     * File path
     *
     * @var string
     */
    private $filePath;

    /**
     * Filters for data processing for import.
     *
     * @var CostStockFilter
     */
    private $helper;

    /**
     * ParserCsvService constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Set filePath
     *
     * @param $filePath string
     *
     * @return $this
     */
    public function setFilePath($filePath)
    {
        $this->filePath = '';
        $file = new SplFileInfo($filePath);
        if(file_exists($filePath) && $file->getExtension() === 'csv') {
            $this->filePath = $filePath;
        }
        return $this;
    }

    /**
     * Get filePath
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set helper
     *
     * @param $helper CostStockFilter
     *
     * @return $this
     */
    public function setHelper(CostStockFilter $helper)
    {
        $this->helper = $helper;
        return $this;
    }

    /**
     * Set mapping before write in database
     */
    protected function setMappingStep()
    {
        $mapping = new MappingStep();
        $mapping->map('[Product Code]','[productCode]');
        $mapping->map('[Product Name]','[productName]');
        $mapping->map('[Product Description]','[productDesc]');
        $mapping->map('[Stock]','[stock]');
        $mapping->map('[Cost in GBP]','[cost]');
        $mapping->map('[Discontinued]','[discontinued]');

        return $mapping;
    }

    /**
     * Convert filed  discontinued before recording in database
     */
    protected function setConvertStep()
    {
        $converterStep = new ConverterStep();
        $converterStep->add(
            function ($input) {
                $dateTime = new \DateTime();
                $input['discontinued'] =
                    ($input['discontinued'] === 'yes') ? $dateTime : null;

                return $input;
            }
        );

        return $converterStep;
    }

    /**
     * Filter data before recording in database
     */
    protected function setDataFilterStep()
    {

        $filterStep = new FilterStep();
        $filterStep->add(
            function ($input) {
                return $this->helper->checkData($input);
            }
        );

        return $filterStep;
    }

    /**
     * Parse a csv file and import data in database
     *
     * @param $entity string
     * @param $testMode boolean
     *
     * @return boolean
     */
    public function parseCSV($entity, $testMode)
    {
        if(empty($this->filePath)) {
            return false;
        }

        $file = new \SplFileObject($this->filePath);
        $csvReader = new CsvReader($file);
        $csvReader->setHeaderRowNumber(0);

        // Create the workflow from the reader
        $workflow = new StepAggregator($csvReader);

        // Create a writer: you need Doctrine’s EntityManager.
        $doctrineWriter = $testMode ? new EmptyWriter() : new DoctrineWriter($this->entityManager, $entity);
        $doctrineWriter->prepare();
        $workflow->addStep($this->setMappingStep())
            ->addStep($this->setDataFilterStep())
            ->addStep($this->setConvertStep())
            ->addWriter($doctrineWriter)
            ->process();

        return true;
    }

}