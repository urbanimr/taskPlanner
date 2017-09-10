<?php
/**
 * Created by PhpStorm.
 * User: marcinurbaniak
 * Date: 10.09.2017
 * Time: 13:02
 */

namespace TaskPlannerBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;



class MailController extends Controller
{
    /**
     *
     * @Route("/testmail", name="test_mail")
     * @Method("GET")
     */

    public function sendEmailAction(){

        $transport = (new \Swift_SmtpTransport('smtp.gmail.org', 25))
            ->setUsername('marcin.urbaniak@gmail.com')
            ->setPassword('password');


        $mailer = new \Swift_Mailer($transport);

        $message = (new \Swift_Message('Wonderful Subject'))
            ->setFrom(['john@doe.com' => 'John Doe'])
            ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
            ->setBody('Here is the message itself');

        $result = $mailer->send($message);



    }

}