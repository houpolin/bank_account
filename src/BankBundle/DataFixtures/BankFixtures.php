<?php

namespace BankBundle\DataFixtures;

use BankBundle\Entity\Trade;
use BankBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BankFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setTotal(1000);
        
        $manager->persist($user);
        $manager->flush();

        $user = $manager->find('BankBundle:User', '1');
        
        $money = 1000;

        $trade = new Trade();
        $trade->setType('1');
        $trade->setMoney($money);
        $trade->setContent('BankFixtures');
        $trade->setBuildTime(date("Y-m-d H:i:s"));
        $trade->setTotal($money);
        $trade->setUser($user);
        $manager->persist($trade);
        $manager->flush();
    }

    /**
     * 建立使用者
     */
    public function createUser(ObjectManager $manager)
    {
        $user = new User();
        $user->setTotal(0);

        $manager->persist($user);
        $manager->flush();

    }
}
