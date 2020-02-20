<?php

namespace BankBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use BankBundle\Service\BankService;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class BankServiceTest extends WebTestCase
{
    /**
     * 測試function DecideDeposit
     */
    public function testDecideDeposit()
    {
        $redis = $this->createMock("Predis\Client");
        
        $redis->expects($this->any())
            ->method("__call")
            ->willReturn(1000);

        $container = $this->createMock("Symfony\Component\DependencyInjection\Container");

        $container->expects($this->any())
            ->method("get")
            ->willReturn($redis);

        $Bankservice = new BankService($container);
        $result = $Bankservice->decideDeposit('1', '1', '1000');

        $this->assertContains('success', $result['info']);
    }

    /**
     * 測試function DecideTotal
     */
    public function testDecideTotal()
    {
        $redis = $this->createMock("Predis\Client");
        $redis->expects($this->any())
            ->method("__call")
            ->willReturn(1000);

        $container = $this->createMock("Symfony\Component\DependencyInjection\Container");

        $container->expects($this->any())
            ->method("get")
            ->willReturn($redis);

        $Bankservice = new BankService($container);
        $result = $Bankservice->decideTotal('1', '1000');

        $this->assertContains('success', $result['info']);
    }

    /**
     * 測試function DecideTotal 餘額不足
     */
    public function testDecideTotalShort()
    {
        $redis = $this->createMock("Predis\Client");
        $redis->expects($this->any())
            ->method("__call")
            ->willReturn(-100);

        $container = $this->createMock("Symfony\Component\DependencyInjection\Container");

        $container->expects($this->any())
            ->method("get")
            ->willReturn($redis);

        $Bankservice = new BankService($container);
        $result = $Bankservice->decideTotal('1', '1000');

        $this->assertContains('error', $result['info']);
    }

    /**
     * 測試function DecideWithdraw
     */
    public function testDecideWithdraw()
    {
        $redis = $this->createMock("Predis\Client");

        $container = $this->createMock("Symfony\Component\DependencyInjection\Container");

        $container->expects($this->any())
            ->method("get")
            ->willReturn($redis);

        $Bankservice = new BankService($container);
        $result = $Bankservice->decideWithdraw('1', '2', '1000', '1000');

        $this->assertContains('success', $result['info']);
    }
}
