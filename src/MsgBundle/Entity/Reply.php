<?php

namespace MsgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 回覆資料表
 *
 * @ORM\Entity
 * @ORM\Table(name="Reply")
 */
class Reply
{
    /**
     * 回覆資料表ID
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * 留言資料表ID
     *
     * @ORM\ManyToOne(targetEntity="Message", cascade="persist")
     * @ORM\JoinColumn(name="Message_id", referencedColumnName="id", nullable=FALSE, onDelete="CASCADE")
     */
    protected $message;

    /**
     * 回覆內容
     *
     * @ORM\Column(name="content", type="text", nullable=FALSE)
     */
    protected $content;

    /**
     * 回覆建立時間
     *
     * @ORM\Column(name="build_time", type="datetime")
     */
    protected $buildTime;

    /**
     * 獲取回覆ID
     *
     * @return $this->id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 設置回覆ID
     *
     * @param integer id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * 獲取留言資料表ID
     *
     * @return $this->message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 設置留言資料表ID
     *
     * @param mixed message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * 獲取回覆內容
     *
     * @return $this->content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 設置回覆內容
     *
     * @param text content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * 獲取回覆建立時間
     *
     * @return $this->buildTime
     */
    public function getBuildTime()
    {
        $datetime = (array)$this->buildTime;
        return substr($datetime['date'], 0, 19);
    }

    /**
     * 設置回覆建立時間
     *
     * @param datetime buildTime
     */
    public function setBuildTime($buildTime)
    {
        $this->buildTime = new \DateTime($buildTime);
    }
}
