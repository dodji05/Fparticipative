<?php

namespace ActeurBundle\Controller;

use ActeurBundle\Entity\InscriptionAttente;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


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
     *@Route("/inscription-feday", name="first_inscription_fedapay")
     *
     */


    public function callbackAction(Request $request)
    {
        \FedaPay\FedaPay::setApiKey($this->getParameter('feday_secret_key'));
        $transaction_id = $request->get('id');
        global $message;// = '';
        $message='';

        global $route;
        $route = 'first_inscription';
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
                    $this->addFlash(
                        'success',
                        'Felicitation!!!! votre transaction s est effectuee avec succes. Veuillez entre le code reçu par sms et par mail dans le formulaire ci-dessous  !!!!!'
                    );
                   return $this->redirectToRoute('code-validation');
                    break;
                case 'canceled':
                    $message = 'Transaction annulée.';
                    $this->addFlash(
                        'notice',
                        'Oups!!!! votre transaction n\'a pas pu aboutie. Verifier si vous avez de fond pour effectuer cette operation. Merci  !!!!!'
                    );
                    $route='first_inscription';
                    break;
                case 'declined':
                    $message = 'Transaction déclinée.';
                    $this->addFlash(
                        'notice',
                        'Oups!!!! votre transaction n\'a pas pu aboutie. Verifier si vous avez de fond pour effectuer cette operation. Merci  !!!!!'
                    );
                    $route ='first_inscription';
                    break;
            }
        } catch(\Exception $e) {
            $this->addFlash(
                'notice',
                'Desole une erreur sest produite veuillez ressayer    '
            );
            return $this->redirectToRoute('first_inscription');

        }
        return $this->redirectToRoute('first_inscription');
    }

    /**
     *
     *@Route("/transcation/annulee", name="feday_transaction_annule")
     *
     */

    public function transcationAnnuleeAction(){

    }
    /**
     *
     *@Route("/transcation/decline", name="feday_transaction_decline")
     *
     */

    public function transcationDeclineAction(){

    }
}