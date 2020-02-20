<?php

namespace BankBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use BankBundle\Entity\Trade;
use BankBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

class UserTest extends WebTestCase
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
        $user = new User();
        $user->setId('1');

        $getId = $user->getId();

        $this->assertEquals('1', $getId);
    }

    /**
     * 測試function getTotal
     * 測試function setTotal
     */
    public function testGetTotal()
    {
        $user = new User();
        $user->setTotal('1000');

        $getTotal = $user->getTotal();

        $this->assertEquals('1000', $getTotal);
    }


    /**
     * 測試function getVersion
     * 測試function setVersion
     */
    public function testGetVersion()
    {
        $user = new User();
        $user->setVersion('1');
  
        $getVersion = $user->getVersion();
        
        $this->assertEquals('1', $getVersion);
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
