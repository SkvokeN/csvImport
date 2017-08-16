<?php

namespace ImportCsvBundle\Filters;

/**
 * class CostStockFilter
 *
 * This service is used to import data from a csv file into a database.
 **/
class CostStockFilter
{
    /**
     * Field name strProductCode
     *
     * @var string
     */
    private $fieldStrProductCode;

    /**
     * Field name Cost
     *
     * @var string
     */
    private $fieldNameCost;

    /**
     * Field name Stock
     *
     * @var string
     */
    private $filedNameStock;

    /**
     * Min value cost
     *
     * @var float
     */
    private $minCost;

    /**
     * Max value cost
     *
     * @var float
     */
    private $maxCost;

    /**
     * Min value stock
     *
     * @var integer
     */
    private $minStock;

    /**
     * Error Import Message
     *
     * @var array
     */
    private $errorImportMessage;

    /**
     * Seccess Import Message
     *
     * @var array
     */
    private $successImportMessage;

    /**
     * CostStockFilter constructor.
     *
     * @param $fieldStrProductCode string
     * @param $fieldNameCost string
     * @param $filedNameStock string
     * @param $minCost float
     * @param $maxCost float
     * @param $minStock float
     */

    public function __construct($fieldStrProductCode, $fieldNameCost, $filedNameStock, $minCost, $maxCost, $minStock)
    {
        $this->fieldStrProductCode = $fieldStrProductCode;
        $this->fieldNameCost = $fieldNameCost;
        $this->filedNameStock = $filedNameStock;
        $this->maxCost = $maxCost;
        $this->minCost = $minCost;
        $this->minStock = $minStock;
    }

    /**
     * Get $errorImportMessage
     *
     * @return array
     */
    public function getErrorImportMessage()
    {
        return $this->errorImportMessage;
    }

    /**
     * Get $successImportMessage
     *
     * @return array
     */
    public function getSuccessImportMessage()
    {
        return $this->successImportMessage;
    }

    /**
     * Business logic
     *
     * @param $input array
     *
     * @return boolean
     */
    public function checkData($input)
    {
        if(!is_numeric($input[$this->fieldNameCost]) || !is_numeric($input[$this->filedNameStock]))
        {
            $this->errorImportMessage[] = $input[$this->fieldStrProductCode].". Error the data in the fields.";
            return false;
        }

        if($input[$this->fieldNameCost] > $this->maxCost)
        {
            $this->errorImportMessage[] = $input[$this->fieldStrProductCode].". Cost more ". $this->maxCost.".";
            return false;
        }

        if($input[$this->fieldNameCost] < $this->minCost && $input[$this->filedNameStock] < $this->minStock)
        {
            $this->errorImportMessage[] = $input[$this->fieldStrProductCode].". Cost is less than ". $this->minCost." and stock is less than ".$this->minStock.".";
            return false;
        }

        $this->successImportMessage[] =  $input[$this->fieldStrProductCode].". Import success!";

        return true;
    }

}