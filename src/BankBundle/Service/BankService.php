<?php

namespace BankBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class BankService
{
    private $container;

    public function __construct(Container $con)
    {
        $this->container = $con;
    }

    /**
     * 判斷存錢
     *
     * @param string $userID UserID
     * @param string $type 處理事項
     * @param Integer $money 金額
     * @return array info 處理狀態
     * @return array total 餘額
     */
    public function decideDeposit($userID, $type, $money)
    {
        $redis = $this->container->get('snc_redis.default');

        $total = $redis->incrby($userID . ':total', $money);

        $redis->multi();

        $data = array(
                'userID' => $userID,
                'type' => $type,
                'money' => $money,
                'total' => $total,
                'date' => date('Y-m-d H:i:s')
            );

        $redis->rpush('trade', json_encode($data));
        $redis->incr($userID . ':version');

        $redis->exec();

        return array('info'=>'success', 'total'=>$total);
    }

    /**
     * 判斷領錢餘額
     *
     * @param string $userID UserID
     * @param Integer $money 金額
     * @return array info 處理狀態
     * @return array total 餘額
     */
    public function decideTotal($userID, $money)
    {
        $redis = $this->container->get('snc_redis.default');

        $total = $redis->decrby($userID . ':total', $money);

        if ($total < 0) {
            $redis->incrby($userID . ':total', $money);

            return array('info'=>'error');
        } else {
            return array('info'=>'success', 'total'=>$total);
        }
    }

    /**
     * 判斷領錢
     *
     * @param string $userID UserID
     * @param string $type 處理事項
     * @param Integer $money 金額
     * @param Integer $total 餘額
     * @return array info 處理狀態
     * @return array total 餘額
     */
    public function decideWithdraw($userID, $type, $money, $total)
    {
        $redis = $this->container->get('snc_redis.default');

        $redis->multi();

        $data = array(
                'userID' => $userID,
                'type' => $type,
                'money' => $money,
                'total' => $total,
                'date' => date('Y-m-d H:i:s')
            );


        $redis->rpush('trade', json_encode($data));
        $redis->incr($userID . ':version');

        $redis->exec();
        
        return array('info'=>'success', 'total'=>$total);
    }
}
