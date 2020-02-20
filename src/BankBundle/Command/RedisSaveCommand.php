<?php

namespace BankBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BankBundle\Entity\User;
use BankBundle\Entity\Trade;


class RedisSaveCommand extends ContainerAwareCommand
{
    /**
     * 設定command指令 以及 描述
     */
    protected function configure()
    {
        $this->setName('redis:save')
            ->setDescription('Redis data will be synchronized to Mysql');
    }
    /**
     * 處理Redis資料儲存至MySQL
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $redis = $this->getContainer()->get('snc_redis.default');

        $bankService = $this->getContainer()->get('LogService');

        $num = $redis->llen('trade');

        for ($i = 0;$i < $num;$i++) {
            
            $redisData = $redis->lpop('trade');

            $data = json_decode($redisData, true);

            $user = $em->getRepository(User::class)->find($data['userID']);

            $trade = new Trade();
            $trade->setType($data['type']);
            $trade->setMoney($data['money']);
            $trade->setContent('');
            $trade->setBuildTime($data['date']);
            $trade->setUser($user);
            $trade->setTotal($data['total']);

            $user->setTotal($data['total']);

            $em->persist($trade);
            $em->flush();
        }

        $output->writeln([
            '更新完成'
        ]);

        $bankService->SuccessLog();
    }
}
