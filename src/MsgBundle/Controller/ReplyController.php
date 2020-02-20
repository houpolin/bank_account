<?php

namespace MsgBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use MsgBundle\Entity\Message;
use MsgBundle\Entity\Reply;
use MsgBundle\Form\ReplyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReplyController extends Controller
{
    /**
     * 回覆頁面
     *
     * @Route("/message/{id}", name="reply")
     * @Method("GET")
     *
     * @param string $id Message's ID
     *
     * @return string title 頁面名稱
     * @return object(MsgBundle\Entity\Message) message
     * @return array replys object(MsgBundle\Entity\Reply)
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository(Message::class)->find($id);

        $reply = new Reply();
        $replys = $em->createQueryBuilder()
            ->select('a')
            ->from(Reply::class, 'a')
            ->leftJoin('a.message','m')
            ->where('m.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();

        return $this->render('reply/index.html.twig', array(
            'title' => '-回覆',
            'message' => $message,
            'replys' => $replys
        ));
    }

    /**
     * 資料新增處理頁面
     *
     * @Route("/reply/{id}", name="reply_create")
     * @Method("POST")
     *
     * @param object(Symfony\Component\HttpFoundation\Request) $request
     * @param string $id Message's ID
     *
     * @return 錯誤訊息
     * @return 導轉到回覆頁面
     */
    public function createAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $message = $em->find(Message::class, $id);

        if ($message === null) {
            return new Response('沒有此資料');
        }

        $reply = new Reply();
        $content = $request->request->get('content');
        if ($content) {
            $reply->setContent($content);
        }
        $reply->setmessage($message);

        $reply->setBuildTime(date("Y-m-d H:i:s"));
        try {
            $em->persist($reply);
            $em->flush();
        } catch (\Exception $e) {
            return new Response($e->getmessage());
        }

        return $this->redirect($this->generateUrl('reply', array('id'=>$id)));
    }

    /**
     * 資料刪除處理頁面
     *
     * @Route("/reply/{id}", name="reply_delete")
     * @Method("DELETE")
     *
     * @param string $rid Reply's ID
     *
     * @return 導轉到回覆頁面
     */
    public function delectAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $reply = $em->getRepository(Reply::class)->find($id);
        
        $message = ($reply->getMessage()->getId());

        $em->remove($reply);
        $em->flush();

        return $this->redirect($this->generateUrl('reply', array('id'=>$message)));
    }
}
