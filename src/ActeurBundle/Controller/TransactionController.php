<?php

namespace ActeurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ActeurBundle\Entity\InscriptionAttente;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class TransactionController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    /***
     *
     *@Route("/callback-transaction-fedpay", name="callback_transaction_fedapay")
     *
     */

    public function callbackAction(Request $request)
    {
        $transaction_id = $request->get('id');
        $message = '';
        $route = '';
        try {
            $transaction = \FedaPay\Transaction::retrieve($transaction_id);
            switch($transaction->status) {
                case 'approved':
                    $message = 'Transaction approuvée.';
                    $em = $this->getDoctrine()->getManager();
                    $session = new Session();
                    $sessionAttente = $session->get("enAttente");


                    $code_validation = md5(uniqid(mt_rand(), true));
                   // $sessionAttente->setCodeInscription($code_validation);

                    // envoie de mail de notification pour connection a son espace
                    $smtpkalo = new \Swift_SmtpTransport('mail07.lwspanel.com', 25);
                    $smtpkalo->setUsername('infostest@yolandadiva.com')
                        ->setPassword('Henry_1024');
                    $mailer = new \Swift_Mailer($smtpkalo);
                    //$ip = $this->container->get('request')->getClientIp();


                    $user_mail = $sessionAttente->getEmail();


                    $message = (new \Swift_Message('Votre code de validation pour la plateforme'))
                        ->setFrom("infostest@yolandadiva.com", "SOUTENIR UN PROJET")
                        ->setTo($user_mail)
                        ->setBody($this->renderView('Email/code_validation.html.twig', [
                            'code' => $code_validation,
                            'porteur_nom' => $sessionAttente->getNom(),
                            'porteur_prenom' => $sessionAttente->getPrenom(),
                        ]))
                        ->setContentType('text/html');
                    $mailer->send($message);

                    $inscription = new InscriptionAttente();
                    $inscription->setCodeInscription( $code_validation);
                    $inscription->setPrenom($sessionAttente->getPrenom());
                    $inscription->setNom($sessionAttente->getNom());
                    $inscription->setEmail($sessionAttente->getPrenom());
                    $inscription->setTelephone($sessionAttente->getTelephone());

                    $em->persist( $inscription);
                    $em->flush();
                   // $route= 'code-validation';

                    // return $this->redirectToRoute('code-validation');
                    break;
                case 'canceled':
                    $message = 'Transaction annulée.';
                    break;
                case 'declined':
                    $message = 'Transaction déclinée.';
                    break;
            }
        } catch(\Exception $e) {
            $message = $e->getMessage();
        }
        return $this->redirectToRoute($route);
    }

}
