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


class RegistrationPromoteurController extends Controller
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
     * @Route("/inscription", name="first_inscription")
     *
     */
    public function preInscriptionAction(Request $request)
    {

//        $inscription = new  InscriptionAttente();
//
//       // $form = $this->createForm('ActeurBundle\Form\Type\InscriptionAttenteType', $inscription);
//        $defaultData = array('message' => 'Type your message here');
//        $form = $this->createFormBuilder($defaultData)
//            ->getForm();
//        /** @var $userManager UserManagerInterface */
//        $form->handleRequest($request);
//        // $logger = new LoggerInterface();
//        $em = $this->getDoctrine()->getManager();
//
//        if ($form->isSubmitted() && $form->isValid()) {
////            dump($form);
////            die();
//
//            $code_validation = md5(uniqid(mt_rand(), true));
//            $inscription->setCodeInscription($code_validation);
//
//            $token = $request->request->get('stripeToken');
//            \FedaPay\FedaPay::setApiKey($this->getParameter('feday_public_key'));
//            $fedaPayClient = $this->get('fedapay_client');
//
//            /** @var User $user */
//            $user = $this->getUser();
//
//            if (!$user->getStripeCustomerId()) {
//                $fedaPayClient->createCustomer($user, $token);
//            } else {
//                $fedaPayClient->updateCustomerCard($user, $token);
//            }
//
//            $fedaPayClient->createInvoiceItem(
//                1000 * 100,
//                $user,
//                'INSCRIPTION A LA PLATEFORME'
//            );
//
//            // guarantee it charges *right* now
//            $fedaPayClient->createInvoice($user, true);
//
//
//
//            // Sauvegades du dons qui vient d'etre effectuee
//
//
//            // envoie de mail de notification pour connection a son espace
//            $smtpkalo = new \Swift_SmtpTransport('mail07.lwspanel.com', 25);
//            $smtpkalo->setUsername('infostest@yolandadiva.com')
//                ->setPassword('Henry_1024');
//            $mailer = new \Swift_Mailer($smtpkalo);
//            //$ip = $this->container->get('request')->getClientIp();
//
//            $user = $this->getUser();
//            //  $user1 =
//            $user_mail = $form->get('email')->getData();
//
//
//            $message = (new \Swift_Message('Votre code de validation pour la plateforme'))
//                ->setFrom("infostest@yolandadiva.com", "SOUTENIR UN PROJET")
//                ->setTo($user_mail)
//                ->setBody($this->renderView('Email/code_validation.html.twig', [
//                    'code' => $code_validation,
//                    'porteur_nom' => $form->get('nom'),
//                    'porteur_prenom' => $form->get('prenom'),
//                ]))
//                ->setContentType('text/html');
//            $mailer->send($message);
////            dump($mailer);
//            die();
//         //   $inscription->setChargeId($charge->id);
//            // $user->setPremium($charge->paid);
//            $em->persist($inscription);
//            $em->flush();
//
//            return $this->redirectToRoute('code-validation');
//
//
//        } else {
////            dump($form->getErrors(true));
////            die();
//        }
//
//
//        return $this->render('@Acteur/Promotteurs/pre-inscription.form.html.twig', [
//
//            // 'form_inscription' => $InscriptionForm->createView(),
//            'stripe_public_key' => $this->getParameter('stripe_public_key'),
//            'feday_public_key' => $this->getParameter('feday_public_key'),
//            'form' => $form->createView()
//        ]);

        $inscription = new  InscriptionAttente();
        $session = new Session();
        $session->remove('enAttente');

        $form = $this->createForm('ActeurBundle\Form\Type\InscriptionAttenteType', $inscription);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('enAttente', $form->getData('acteur_bundle_inscription_attente'));
            return $this->redirectToRoute('first_inscription_payement');
        }

        return $this->render('@Acteur/Promotteurs/pre-inscription.form.html.twig', [

            // 'form_inscription' => $InscriptionForm->createView(),

            'form' => $form->createView()
        ]);

    }
    /**
     *
     *
     *@Route("/inscription-payement", name="first_inscription_payement")
     *
     */
    public function preInscriptionPayAction (Request $request){
        $session = new Session();
        $sessionAttente = $session->get("enAttente");
//        var_dump( $sessionAttente->getNom());
//        die();
        \FedaPay\FedaPay::setApiKey($this->getParameter('feday_secret_key'));
       // $fedaPayClient = $this->get('fedapay_client');

        try {
            $transaction = \FedaPay\Transaction::create(
                $this->fedapayTransactionData(10000, $sessionAttente)
            );
            $token = $transaction->generateToken();
        return $this->redirect($token->url);
        } catch(\Exception $e) {
          return $e->getMessage();
        }
    }

    private function fedapayTransactionData($frais,$user)
    {
        $customer_data = [
            'firstname' => $user->getNom(),
            'lastname' => $user->getPrenom(),
            'email' => $user->getEmail(),
            'phone_number' => [
                'number'  => $user->getTelephone(),
                'country' => 'bj'
            ]
        ];
        return [
            'description' => 'frais dinscription',
            'amount' => $frais,
            'currency' => ['iso' => 'XOF'],
            'callback_url' => 'http://localhost/Fparticipative/web/app_dev.php/register/porteur/code-validation',
            'customer' => $customer_data
        ];
    }

    /***
     *
     * @Route("/inscription-etape", name="first_step_inscription")
     *
     */
    public function preInscriptionFindrAction(Request $request)
    {
        $inscription = new  InscriptionAttente();
        $form = $this->createForm('ActeurBundle\Form\Type\InscriptionAttenteType', $inscription);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('first_inscription_payement');
        }

        return $this->render('@Acteur/Promotteurs/pre-inscription.form.html.twig', [

            // 'form_inscription' => $InscriptionForm->createView(),

            'form' => $form->createView()
        ]);

    }

    /***
     *
     *@Route("/inscription-transaction", name="inscription_transaction")
     *
     */

    public function callback(Request $request)
    {
        $transaction_id = $request->get('id');
        $message = '';
        try {
            $transaction = \FedaPay\Transaction::retrieve($transaction_id);
            switch($transaction->status) {
                case 'approved':
                    $message = 'Transaction approuvée.';
                    $em = $this->getDoctrine()->getManager();
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
//            dump($mailer);
//            die();
                    //   $inscription->setChargeId($charge->id);
                    // $user->setPremium($charge->paid);
                    $em->persist($sessionAttente);
                    $em->flush();

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
        return view('callback', compact('message'));
    }


}

