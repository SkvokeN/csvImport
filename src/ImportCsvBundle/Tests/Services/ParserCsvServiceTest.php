<?php
namespace ImportCsvBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManager;
use ImportCsvBundle\Services\ParserCsvService;
use ImportCsvBundle\Services\Filters\CostStockFilter;

class ParserCsvServiceTest extends KernelTestCase
{
    /**
     * @var ParserCsvService
     */
    protected $tester;

    /**
     * @var EntityManager
     */
    protected $em;


    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->tester = new ParserCsvService($this->em);
        $this->tester->setHelper(new CostStockFilter('productCode','cost', 'stock', 5, 1000, 10));
    }

    public function testSetFilePath()
    {
        $tester = clone $this->tester;
        $tester->setFilePath('src/ImportCsvBundle/Tests/Resources/stock.csv');
        $this->assertEquals($tester->getFilePath(), 'src/ImportCsvBundle/Tests/Resources/stock.csv');

        $tester->setFilePath('src/ImportCsvBundle/Tests/Resources/stock.txt');
        $this->assertEquals($tester->getFilePath(), '');

        $tester->setFilePath('src/ImportCsvBundle/Tests/Resources/stocks.txt');
        $this->assertEquals($tester->getFilePath(), '');

    }



    public function testParseCSV()
    {
        $tester = clone $this->tester;

        $tester->setFilePath('src/ImportCsvBundle/Tests/Resources/stock.csv');
        $this->assertEquals($tester->parseCSV('ImportCsvBundle:Product', true), true);

    }


}