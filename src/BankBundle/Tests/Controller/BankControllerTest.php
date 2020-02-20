<?php

namespace BankBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use BankBundle\Entity\Trade;
use BankBundle\Controller\BankController;
use Symfony\Component\HttpFoundation\Request;
use BankBundle\Service\BankService;

class BankControllerTest extends WebTestCase
{
    /**
     * 測試function depositAcrion
     */
    public function testDepositSuccess()
    {
        $request = new Request(array('POST'), array(
            'id' => '1',
            'type' => '1', 
            'money' => 1000,
            'content' => '測試'
        ));

        $service = $this->createMock("BankBundle\Service\BankService");

        $service->expects($this->any())
            ->method("decideDeposit")
            ->willReturn(array('info'=>'success',  'total'=>'1000'));

        $container = $this->createMock("Symfony\Component\DependencyInjection\Container");

        $container->expects($this->any())
            ->method("get")
            ->willReturn($service);

        $bankController = new BankController();
        $bankController->setContainer($container);
        $content = $bankController->depositAction($request)->getContent();
        $content_arr = json_decode($content, true);

        $this->assertEquals(1000, $content_arr['total']);
    }

    /**
     * 測試function depositAcrion 交易失敗
     */
    public function testDepositError()
    {
        $request = new Request(array('POST'), array(
            'id' => '1',
            'type' => '1', 
            'money' => 1000,
            'content' => '測試'
        ));

        $service = $this->createMock("BankBundle\Service\BankService");

        $service->expects($this->any())
            ->method("decideDeposit")
            ->willReturn(array('info'=>'error'));

        $container = $this->createMock("Symfony\Component\DependencyInjection\Container");

        $container->expects($this->any())
            ->method("get")
            ->willReturn($service);

        $bankController = new BankController();
        $bankController->setContainer($container);

        $content = $bankController->depositAction($request)->getContent();
        $content_arr = json_decode($content, true);

        $this->assertEquals('交易失敗', $content_arr['info']);
    }

    /**
     * 測試function depositAcrion 種類錯誤
     */
    public function testDepositTypeError()
    {
        $request = new Request(array('POST'), array(
            'id' => '1',
            'type' => '2', 
            'money' => 'test',
            'content' => '測試'
        ));

        $bankController = new BankController();
        $content = $bankController->depositAction($request)->getContent();

        $data = json_decode($content, true);

        $this->assertEquals('交易發生錯誤', $data['info']);
    }

    /**
     * 測試function withdrawAcrion
     */
    public function testWithdraw()
    {
        $request = new Request(array('POST'), array(
            'id' => '1',
            'type' => '2', 
            'money' => 100,
            'content' => '測試'
        ));

        $decideTotal = $this->createMock("BankBundle\Service\BankService");

        $decideTotal->expects($this->any())
            ->method("decideTotal")
            ->willReturn(100);

        $container = $this->createMock("Symfony\Component\DependencyInjection\Container");

        $container->expects($this->any())
            ->method("get")
            ->willReturn($decideTotal);


        $decideWithdraw = $this->createMock("BankBundle\Service\BankService");

        $decideWithdraw->expects($this->any())
            ->method("decideWithdraw")
            ->willReturn(array('info'=>'success', 'total'=>'100'));
        
        $container = $this->createMock("Symfony\Component\DependencyInjection\Container");

        $container->expects($this->any())
            ->method("get")
            ->willReturn($decideWithdraw);

        $bankController = new BankController();
        $bankController->setContainer($container);
        $content = $bankController->withdrawAction($request)->getContent();
        $content_arr = json_decode($content, true);

        $this->assertEquals(100, $content_arr['total']);
    }

    /**
     * 測試function withdrawAcrion 交易失敗
     */
    public function testWithdrawError()
    {
        $request = new Request(array('POST'), array(
            'id' => '1',
            'type' => '2', 
            'money' => 100,
            'content' => '測試'
        ));

        $decideWithdraw = $this->createMock("BankBundle\Service\BankService");

        $decideWithdraw->expects($this->any())
            ->method("decideWithdraw")
            ->willReturn(array('info'=>'error'));
        
        $container = $this->createMock("Symfony\Component\DependencyInjection\Container");

        $container->expects($this->any())
            ->method("get")
            ->willReturn($decideWithdraw);

        $bankController = new BankController();
        $bankController->setContainer($container);
        $content = $bankController->withdrawAction($request)->getContent();
        $content_arr = json_decode($content, true);

        $this->assertEquals('交易失敗', $content_arr['info']);
    }

    /**
     * 測試function depositAcrion 種類錯誤
     */
    public function testWithdrawTypeError()
    {
        $request = new Request(array('POST'), array(
            'id' => '1',
            'type' => '1', 
            'money' => '1000',
            'content' => '測試'
        ));

        $testController = new BankController();
        $content = $testController->withdrawAction($request)->getContent();

        $data = json_decode($content, true);

        $this->assertEquals('交易發生錯誤', $data['info']);
    }

    /**
     * 測試function depositAcrion 餘額不足
     */
    public function testWithdrawTotalError()
    {
        $request = new Request(array('POST'), array(
            'id' => '1',
            'type' => '2', 
            'money' => '100000',
            'content' => '測試'
        ));

        $decideTotal = $this->createMock("BankBundle\Service\BankService");

        $decideTotal->expects($this->any())
            ->method("decideTotal")
            ->willReturn(array('info'=>'error'));

        $container = $this->createMock("Symfony\Component\DependencyInjection\Container");

        $container->expects($this->any())
            ->method("get")
            ->willReturn($decideTotal);

        $bankController = new BankController();
        $bankController->setContainer($container);

        $content = $bankController->withdrawAction($request)->getContent();

        $data = json_decode($content, true);

        $this->assertEquals('餘額不足，交易失敗', $data['info']);
    }
}
