<?php

namespace BankBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

class BankController extends Controller
{
    /**
     * 存款
     *
     * @Route("/bank/deposit", name="bank_deposit")
     * @Method({"POST","GET"})
     *
     * @param object(Symfony\Component\HttpFoundation\Request) $request 存款資料
     * @return json info 交易發生錯誤
     * @return json money 存款金額
     * @return json total 帳戶餘額
     * @return json info 交易訊息
     */
    public function depositAction(Request $request)
    {
        $request = $request->request->all();
        
        if ($request['type'] != 1) {
            return new JsonResponse(array(
                'info' => '交易發生錯誤'
            ));
        }

        $bankService = $this->container->get('BankService');

        $result = $bankService->decideDeposit($request['id'], $request['type'], $request['money']);
        
        if ($result['info'] == 'success') {
            return new JsonResponse(array(
                'money' => $request['money'],
                'total' => $result['total'],
                'info' => '交易成功'
            ));        
        } else {
            return new JsonResponse(array(
                'info' => '交易失敗'
            ));
        }
    }

    /**
     * 提款
     *
     * @Route("/bank/withdraw", name="bank_withdraw")
     * @Method({"POST"})
     *
     * @param object(Symfony\Component\HttpFoundation\Request) $request 提款資料
     * @return json info 交易發生錯誤
     * @return json info 餘額不足
     * @return json money 存款金額
     * @return json total 帳戶餘額
     * @return json info 交易訊息
     */
    public function withdrawAction(Request $request)
    {
        $request = $request->request->all();

        if ($request['type'] != 2) {
            return new JsonResponse(array(
                'info' => '交易發生錯誤'
            ));
        }
        
        $bankService = $this->container->get('BankService');

        $checkTotal = $bankService->decideTotal($request['id'], $request['money']);

        if ($checkTotal['info'] === 'error') {
            return new JsonResponse(array(
                'info' => '餘額不足，交易失敗'
            ));        
        }

        $result = $bankService->decideWithdraw($request['id'], $request['type'], $request['money'], $checkTotal['total']);

        if ($result['info'] == 'success') {
            return new JsonResponse(array(
                'money' => $request['money'],
                'total' => $result['total'],
                'info' => '交易成功'
            ));
        } else {
            return new JsonResponse(array(
                'info' => '交易失敗'
            ));
        }
    }
}
