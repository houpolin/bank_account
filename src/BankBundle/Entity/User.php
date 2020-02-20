<?php

namespace BankBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 銀行帳戶表
 *
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User
{
    /**
     * 銀行帳戶表ID
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\OneToMany(targetEntity="Reply", cascade="remove")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * 餘額
     *
     * @ORM\Column(name="total", type="integer")
     */
    protected $total;

    /** 
     * 版號
     *
     * @ORM\Version
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $version = 1;

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
     * 獲取版號
     *
     * @return $this->version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * 設置版號
     *
     * @param text version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
}
