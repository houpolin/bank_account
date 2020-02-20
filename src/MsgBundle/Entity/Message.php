<?php

namespace MsgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 留言資料表
 *
 * @ORM\Entity(repositoryClass="MsgBundle\Repository\MessageRepository")
 * @ORM\Table(name="Message")
 */
class Message
{
    /**
     * 留言資料表ID
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\OneToMany(targetEntity="Reply", cascade="remove")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * 留言標題
     *
     * @ORM\Column(name="title", type="string", length=40, nullable=FALSE)
     */
    protected $title;

    /**
     * 留言內容
     *
     * @ORM\Column(name="content", type="text", nullable=FALSE)
     */
    protected $content;

    /**
     * 留言更新時間
     *
     * @ORM\Column(name="update_time", type="datetime", nullable=TRUE)
     */
    protected $updateTime;

    /**
     * 留言建立時間
     *
     * @ORM\Column(name="build_time", type="datetime")
     */
    protected $buildTime;

    /**
     * 獲取留言ID
     *
     * @return $this->id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 設置留言ID
     *
     * @param integer id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * 獲取留言標題
     *
     * @return $this->title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * 設置留言標題
     *
     * @param string title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * 獲取留言內容
     *
     * @return $this->content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 設置留言內容
     *
     * @param text content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * 獲取留言更新時間
     *
     * @return $this->updateTime
     */
    public function getUpdateTime()
    {
            
        $datetime = (array)$this->updateTime;
        if($datetime == null ){
            return null;
        }
        return substr($datetime['date'], 0, 19);
    }

    /**
     * 設置留言更新時間
     *
     * @param datetime updateTime
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = new \DateTime($updateTime);
    }

    /**
     * 獲取留言建立時間
     *
     * @return $this->buildTime
     */
    public function getBuildTime()
    {
        $datetime = (array)$this->buildTime;
        return substr($datetime['date'], 0, 19);
    }

    /**
     * 設置留言建立時間
     *
     * @param datetime buildTime
     */
    public function setBuildTime($buildTime)
    {
        $this->buildTime = new \DateTime($buildTime);
    }
}
