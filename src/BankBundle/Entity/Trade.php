<?php

namespace BankBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 銀行交易明細資料表
 *
 * @ORM\Entity(repositoryClass="BankBundle\Repository\BankRepository")
 * @ORM\Table(name="Trade")
 */
class Trade
{
    /**
     * 銀行交易資料表ID
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * 銀行帳戶表ID
     *
     * @ORM\ManyToOne(targetEntity="User", cascade="persist")
     * @ORM\JoinColumn(name="User_id", referencedColumnName="id", nullable=FALSE, onDelete="CASCADE")
     */
    protected $user;

    /**
     * 類型(1.存入2.取出)
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    protected $type;

    /**
     * 金額
     *
     * @ORM\Column(name="money", type="integer", nullable=false)
     */
    protected $money;

    /**
     * 餘額
     *
     * @ORM\Column(name="total", type="integer", nullable=false)
     */
    protected $total;

    /**
     * 備註
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    protected $content;

    /**
     * 銀行交易建立時間
     *
     * @ORM\Column(name="build_time", type="datetime")
     */
    protected $buildTime;

    /**
     * 獲取銀行交易ID
     *
     * @return $this->id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 設置銀行交易ID
     *
     * @param integer id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * 獲取User_id
     *
     * @return $this->user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * 設置User_id
     *
     * @param mixed user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * 獲取類型(1.存入2.取出)
     *
     * @return $this->type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * 設置類型(1.存入2.取出)
     *
     * @param string type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * 獲取金額
     *
     * @return $this->money
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * 設置金額
     *
     * @param string money
     */
    public function setMoney($money)
    {
        $this->money = $money;
    }

    /**
     * 獲取餘額
     *
     * @return $this->total
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * 設置餘額
     *
     * @param string total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }    

    /**
     * 獲取備註
     *
     * @return $this->content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 設置備註
     *
     * @param text content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * 獲取建立時間
     *
     * @return $this->buildTime
     */
    public function getBuildTime()
    {
        $datetime = (array)$this->buildTime;
        return substr($datetime['date'], 0, 19);
    }

    /**
     * 設置建立時間
     *
     * @param datetime buildTime
     */
    public function setBuildTime($buildTime)
    {
        $this->buildTime = new \DateTime($buildTime);
    }
}
