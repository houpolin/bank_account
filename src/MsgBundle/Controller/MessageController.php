<?php

namespace MsgBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use MsgBundle\Entity\Message;
use MsgBundle\Form\MessageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MessageController extends Controller
{
    /**
     * 全部資料頁面
     *
     * @Route("/message", name="message")
     * @Method("GET")
     *
     * @return string title 頁面名稱
     * @return string total 留言總數
     * @return object(Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination) data 留言分頁模組顯示
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $message = $em->getRepository(Message::class)->createQueryBuilder('n');
        $data = $em->getRepository(Message::class)->findAll();
        

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($message, $request->query->getInt('page', 1));

        $message = $em->getRepository(Message::class);
        $total = $message->getCount();

        return $this->render('message/index.html.twig', array(
            'title' => '-首頁',
            'total' => $total,
            'data' => $pagination
        ));
    }

    /**
     * 資料新增頁面
     *
     * @Route("/message/new", name="message_create")
     * @Method({"GET","POST"})
     *
     * @param object(Symfony\Component\HttpFoundation\Request) $request
     *
     * @return 錯誤訊息
     * @return 全部資料頁面
     * @return 導轉到資料新增頁面
     */
    public function newAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $message = new Message();

            $title = $request->request->get('title');
            $content = $request->request->get('content');

            if ($title) {
                $message->setTitle($title);
            }
            if ($content) {
                $message->setContent($content);
            }
            $message->setBuildTime(date("Y-m-d H:i:s"));
            
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($message);
                $em->flush();
            } catch (\Exception $e) {
                return new Response($e->getmessage());
                exit;
            }
        
            return $this->redirect($this->generateUrl('message'));
        }

        return $this->render('message/new.html.twig', array(
            'title' => '-新增',
        ));
    }

    /**
     * 資料修改頁面
     *
     * @Route("/message/edit/{id}", name="message_update", requirements={"id": "\d+"})
     * @Method({"GET","PUT"})
     *
     * @param object(Symfony\Component\HttpFoundation\Request) $request
     * @param string $id|Message's ID
     *
     * @return string title 頁面名稱
     * @return object(MsgBundle\Entity\Message) message
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository(Message::class)->find($id);

        if ($message === null) {
            return new Response('沒有此資料');
        }

        if ($request->isMethod('PUT') ) {

            $title = $request->request->get('title');
            $content = $request->request->get('content');

            $message->setTitle($title);
            $message->setContent($content);
            $message->setUpdateTime(date("Y-m-d H:i:s"));
        
            $em->persist($message);
            $em->flush();
        }

        return $this->render('message/edit.html.twig', array(
            'title' => '-修改',
            'message' => $message
        ));
    }

    /**
     * 資料刪除處理頁面
     *
     * @Route("/message/{id}", name="message_delete")
     * @Method("DELETE")
     *
     * @param string $id Message's ID
     *
     * @return 全部資料頁面
     */
    public function delectAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository(Message::class)->find($id);

        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute('message');
    }

    /**
     * 批次新增
     *
     * @Route("/message/batch/create", name="batch_create")
     *
     * @return 花費時間並導回全部資料頁面
     */
    public function batchCreate()
    {
        $time_start = microtime(true);
        
        $em = $this->getDoctrine()->getManager();
        $batchSize = 20;
        for ($i=0;$i<1000;++$i) {
            $Message = new Message();
            $Message->setTitle("title" . $i);
            $Message->setContent("content" . $i);
            $Message->setBuildTime(date("Y-m-d H:i:s"));
        
            $em->persist($Message);
            if (($i % $batchSize) === 0) {
                $em->flush();
                $em->clear();
            }
        }
        $em->flush();
        $em->clear();

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $path = $this->generateUrl('message');
        return new Response('<script type="text/javascript">alert("新增資料成功\n\r花費' . $time . '秒");window.location.href="' . $path . '";</script>');
    }

    /**
     * 批次修改
     *
     * @Route("/message/batch/update", name="batch_update")
     *
     * @return 花費時間並導回全部資料頁面
     */
    public function batchUpdate()
    {
        $time_start = microtime(true);

        $em = $this->getDoctrine()->getManager();
        $q = $em->createQueryBuilder()
            ->select('m')
            ->from(Message::class, 'm')
            ->getQuery();

        $iterableResult = $q->iterate();

        $num = 0;
        $error = '';

        $batchSize = 20;
        foreach ($iterableResult as $row) {
            $MessageBoard = $row[0];
            $MessageBoard->setTitle($row[0]->getTitle() . $num);
            $MessageBoard->setContent($row[0]->getContent() . $num);
            $MessageBoard->setUpdateTime(date("Y-m-d H:i:s"));

            if (($num % $batchSize) === 0) {
                $em->flush();
                $em->clear();
            } 
            ++$num;
        }
        $em->flush();
        $em->clear();

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $path = $this->generateUrl('message');
        return new Response('<script type="text/javascript">alert("修改資料成功\n\r花費' . $time . '秒");window.location.href="' . $path . '";</script>');
    }

    /**
     * 批次刪除
     *
     * @Route("/message/batch/delete", name="batch_delete")
     *
     * @return 花費時間並導回全部資料頁面
     */
    public function batchDelete()
    {
        $time_start = microtime(true);
        
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQueryBuilder()
            ->select('m')
            ->from(Message::class, 'm')
            ->getQuery();
        $iterableResult = $q->iterate();

        $num = 0;
        $batchSize = 20;
        while (($row = $iterableResult->next()) !== false) {
            $em->remove($row[0]);
            if (($num % $batchSize) === 0) {
                $em->flush();
                $em->clear();
            }
            ++$num;
        }
        $em->flush();
        $em->clear();

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $path = $this->generateUrl('message');
        return new Response('<html><script type="text/javascript">alert("刪除資料成功\n\r花費' . $time . '秒");window.location.href="' . $path . '";</script></html>');        
    }
}
