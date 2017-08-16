<?php
namespace ImportCsvBundle\Writer;

use Port\Writer;

/**
 * class EmptyWriter
 *
 * Class for testing imports without recording data to the database.
 **/

class EmptyWriter implements Writer
{
    /**
     * Prepare the writer before writing the items
     */
    public function prepare()
    {

    }

    /**
     * Write one data item
     *
     * @param array $item The data item with converted values
     */
    public function writeItem(array $item)
    {

    }

    /**
     * Wrap up the writer after all items have been written
     */
    public function finish()
    {

    }
}