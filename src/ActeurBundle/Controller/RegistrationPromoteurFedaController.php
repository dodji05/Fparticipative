<?php

namespace ActeurBundle\Controller;

use ActeurBundle\Entity\InscriptionAttente;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RegistrationPromoteurFedaController extends Controller
{
    /**
     *
     *
     * @Route("/register/porteur", name="porteur_register")
     *
     */
    public function registerAction()
    {
        return $this->container
            ->get('pugx_multi_user.registration_manager')
            ->register('AdminBundle\Entity\Porteur');
    }

    /**
     *
     *
     *@Route("/inscription-feday", name="first_inscription_fedapay")
     *
     */
    public function callbackAction (Request $request){
        \FedaPay\FedaPay::setApiKey($this->getParameter('feday_secret_key'));

        $transaction_id = $request->get('id');
        $message = '';
        $route = '';

        try {
            $transaction = \FedaPay\Transaction::retrieve($transaction_id);

            switch($transaction->status) {
                case 'approved':

                    $message = 'Transaction approuvée.';

                    $session = new Session();
                    $sessionAttente = $session->get("enAttente");

                    $code_validation = md5(uniqid(mt_rand(), true));
                    $sessionAttente->setCodeInscription($code_validation);

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
//
                    $inscription = new InscriptionAttente();
                    $inscription->setNom( $sessionAttente->getNom());
                    $inscription->setPrenom($sessionAttente->getPrenom());
                    $inscription->setCodeInscription( $code_validation);
                    $inscription->setTelephone($sessionAttente->getTelephone());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($inscription);
                    $em->flush();
                    $route= 'code-validation';

                     return $this->redirectToRoute('code-validation');
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
        //return $this->redirectToRoute($route);
        return $this->redirectToRoute('code-validation');

    }
}