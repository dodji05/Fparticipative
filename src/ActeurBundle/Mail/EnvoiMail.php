<?php
/**
 * Created by PhpStorm.
 * User: dodji
 * Date: 26/08/18
 * Time: 18:04
 */

namespace ActeurBundle\Mail;


class EnvoiMail
{

    private $host;
    private $mailer;
    /**
     * EnvoiMail constructor.
     */
    public function __construct($host , $username, $pwd)
    {
        $smtpkalo  = new \Swift_SmtpTransport($host,25);
        $smtpkalo->setUsername($username)
            ->setPassword($pwd);
        $this->mailer = new \Swift_Mailer($smtpkalo);
    }
//    public function  envoiMsg ($sujet,$from,$to,$template,$file=null){
//        $message = (new \Swift_Message('[NOUVEAU PROJET] Un nouveau projet a été soumis'))
//            ->setFrom($this->getParameter('mailer_user'))
//            ->setTo($this->getUser()->getEmail());
//            if($file !=null){
//                $message ->attach(Swift_Attachment::fromPath($file);
//            };
//
//            ->setBody(
//                $this->renderView(
//                // app/Resources/views/Emails/registration.html.twig
//                    'Email/accuse_reception.html.twig',
//                    array('projet' => $projet)
//                ),
//                'text/html'
//            );
//        $mailer->send($message);
//
//    }
}