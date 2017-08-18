<?php
namespace ImportCsvBundle\Tests\Services\Filters;

use ImportCsvBundle\Services\Filters\CostStockFilter;

class CostStockFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckData() {

        $costStockFilter = new CostStockFilter('productCode','cost', 'stock', 5, 1000, 10);

        $result = $costStockFilter->checkData(['cost' => 'cost',
                                               'stock' => 'asd23',
                                               'productCode' => 'TT']);

        $this->assertEquals($result, false);

        $result = $costStockFilter->checkData(['cost' => '2000',
                                               'stock' => '12',
                                               'productCode' => 'TT']);

        $this->assertEquals($result, false);

        $result = $costStockFilter->checkData(['cost' => '3',
                                               'stock' => '2',
                                               'productCode' => 'TT']);

        $this->assertEquals($result, false);

        $result = $costStockFilter->checkData(['cost' => '12',
                                               'stock' => '100',
                                               'productCode' => 'TT']);

        $this->assertEquals($result, true);

    }
}