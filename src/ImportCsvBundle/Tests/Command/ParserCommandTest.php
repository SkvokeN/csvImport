<?php
namespace ImportCsvBundle\Tests\Command;


use ImportCsvBundle\Command\ParserCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ParserCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $application->add(new ParserCommand());

        $command = $application->find('app:parser');
        $commandTester = new CommandTester($command);

        //test mode
        $commandTester->execute(
            ['command' => $command->getName(),
             'fileName' => 'stock.csv',
             'testMode' => true ]
        );

        $output = $commandTester->getDisplay();
        $this->assertContains('Test mode!',$output);

        //work mode
        $commandTester->execute(
            ['command' => $command->getName(),
                'fileName' => 'stock.csv',
                'testMode' => false ]
        );
        $output = $commandTester->getDisplay();
        $this->assertContains('War mode!',$output);


        $commandTester->execute(
            ['command' => $command->getName(),
                'fileName' => 'stocsk.csv',
                'testMode' => true ]
        );
        $output = $commandTester->getDisplay();
        $this->assertContains('File not found!',$output);

    }

}