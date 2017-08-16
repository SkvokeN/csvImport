<?php
namespace ImportCsvBundle\Tests\Filters;

use ImportCsvBundle\Filters\CostStockFilter;

class CostStockFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckData() {

        $costStockFilter = new CostStockFilter('strproductcode','cost', 'stock', 5, 1000, 10);

        $result = $costStockFilter->checkData(['cost' => 'cost',
                                               'stock' => 'asd23',
                                               'strproductcode' => 'TT']);

        $this->assertEquals($result, false);

        $result = $costStockFilter->checkData(['cost' => '2000',
                                               'stock' => '12',
                                               'strproductcode' => 'TT']);

        $this->assertEquals($result, false);

        $result = $costStockFilter->checkData(['cost' => '3',
                                               'stock' => '2',
                                               'strproductcode' => 'TT']);

        $this->assertEquals($result, false);

        $result = $costStockFilter->checkData(['cost' => '12',
                                               'stock' => '100',
                                               'strproductcode' => 'TT']);

        $this->assertEquals($result, true);

    }
}