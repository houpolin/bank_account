<?php

namespace BankBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use BankBundle\Entity\Trade;
use BankBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

class TradeTest extends WebTestCase
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
     * 測試function getId
     * 測試function setId
     */
    public function testGetId()
    {
        $trade = new Trade();
        $trade->setId('1');

        $getId = $trade->getId();

        $this->assertEquals('1', $getId);
    }

    /**
     * 測試function getType
     * 測試function setType
     */
    public function testGetType()
    {
        $trade = new Trade();
        $trade->setType('1');

        $getType = $trade->getType();

        $this->assertEquals('1', $getType);
    }

    /**
     * 測試function getMoney
     * 測試function setMoney
     */
    public function testGetMoney()
    {
        $trade = new Trade();
        $trade->setMoney('1000');

        $getMoney = $trade->getMoney();

        $this->assertEquals('1000', $getMoney);
    }

    /**
     * 測試function getTotal
     * 測試function setTotal
     */
    public function testGetTotal()
    {
        $trade = new Trade();
        $trade->setTotal('1000');

        $getTotal = $trade->getTotal();

        $this->assertEquals('1000', $getTotal);
    }

    /**
     * 測試function getContent
     * 測試function setContent
     */
    public function testGetContent()
    {
        $trade = new Trade();
        $trade->setContent('測試');

        $getContent = $trade->getContent();

        $this->assertEquals('測試', $getContent);
    }

    /**
     * 測試function getBuildTime
     * 測試function setBuildTime
     */
    public function testGetBuildTime()
    {
        $trade = new Trade();
        $trade->setBuildTime('2017-12-13 00:00:00');

        $getBuildTime = $trade->getBuildTime();

        $this->assertEquals('2017-12-13 00:00:00', $getBuildTime);
    }

    /**
     * 測試function getUser
     * 測試function setUser
     */
    public function testGetUser()
    {
        $user = new User();
        $user->setTotal('0');
  
        $this->em->persist($user);
        $this->em->flush();

        $userEntity = $this->em->find('BankBundle:User','1');

        $trade = new Trade();
        $trade->setUser($userEntity);

        $getUser = $trade->getUser();

        $this->assertEquals($userEntity, $getUser);
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
