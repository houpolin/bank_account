<?php

namespace BankBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use BankBundle\Command\RedisSaveCommand;
use Symfony\Component\Console\Tester\CommandTester;
use BankBundle\Service\BankService;
use BankBundle\DataFixtures\BankFixtures;
use BankBundle\Entity\User;

class RedisSaveCommandTest extends WebTestCase
{
    public function setUp()
    {
        
        $client = static::createClient();
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();


        $deposit = new BankService($container);
        $deposit->decideDeposit('1', '1', '1000');
        

        $fixture = new BankFixtures();
        $fixture->createUser($entityManager);

    }

    /**
     * 測試function Execute
     */
    public function testExecute()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $application->add(new RedisSaveCommand());
        $command = $application->find('redis:save');

        $commandTester = new CommandTester($command);
        $commandTester->execute(array());

        $output = $commandTester->getDisplay();
        $this->assertContains('更新完成', $output);
    }
}
