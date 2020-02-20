<?php

namespace BankBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use BankBundle\Entity\Trade;
use BankBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use BankBundle\DataFixtures\BankFixtures;

class BankRepositoryTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $application = new Application(static::$kernel);
        $application->setAutoExit(false);
        $application->run(new StringInput("doctrine:schema:drop --force --env=test --quiet"));
        $application->run(new StringInput("doctrine:schema:update --force --env=test --quiet"));

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * 測試function getLastTotal
     */
    public function testLastTotal()
    {
        $fixture = new BankFixtures();
        $fixture->load($this->em);
    
        $user = $this->em->find('BankBundle:User', '1');
        
        $trade = new Trade();
        $trade->setType('1');
        $trade->setMoney('1000');
        $trade->setContent('BankFixtures');
        $trade->setBuildTime(date("Y-m-d H:i:s"));
        $trade->setTotal('1000');
        $trade->setUser($user);

        $user->setTotal('1000');

        $this->em->persist($trade);
        $this->em->flush();

        $tradeTotal = $this->em
            ->getRepository(Trade::class)
            ->findOneBy(array(),array('id'=>'DESC'))->getTotal();
        $total =  $this->em->getRepository(Trade::class)->getLastTotal();

        $this->assertEquals($tradeTotal, $total);
    }

    /**
     * 測試function getLastTotal
     */
    public function testLastTotal_err()
    {
        $total =  $this->em->getRepository(Trade::class)->getLastTotal();

        $this->assertEquals(0, $total);
    }

    /**
     * 測試function getCount
     */
    public function testCount()
    {
        $trade = $this->em
            ->getRepository(Trade::class)
            ->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $count =  $this->em->getRepository(Trade::class)->getCount();

        $this->assertEquals($trade, $count);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }

}
