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
    public function preInscriptionAction (Request $request){

        $inscription =  new  InscriptionAttente();

        $form = $this->createForm('ActeurBundle\Form\Type\InscriptionAttenteType', $inscription);
        /** @var $userManager UserManagerInterface */
        $form->handleRequest($request);
        // $logger = new LoggerInterface();
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {

            $code_validation = md5(uniqid(mt_rand(), true));
            $inscription->setCodeInscription($code_validation);

            $token = $request->request->get('stripeToken');
            \FedaPay\FedaPay::setApiKey($this->getParameter('feaday_secret_key'));
            $fedaPayClient = $this->get('fedapay_client');

            /** @var User $user */
            $user = $this->getUser();

            if (!$user->getStripeCustomerId()) {
                $fedaPayClient->createCustomer($user, $token);
            } else {
                $fedaPayClient->updateCustomerCard($user, $token);
            }

                $fedaPayClient->createInvoiceItem(
                    1000 * 100,
                    $user,
                   'INSCRIPTION A LA PLATEFORME'
                );

            // guarantee it charges *right* now
            $fedaPayClient->createInvoice($user, true);



            // envoie de mail de notification pour connection a son espace
            $smtpkalo  = new \Swift_SmtpTransport('mail07.lwspanel.com',25);
            $smtpkalo->setUsername('infostest@yolandadiva.com')
                ->setPassword('Henry_1024');
            $mailer = new \Swift_Mailer($smtpkalo);
            //$ip = $this->container->get('request')->getClientIp();

            $user = $this->getUser();
            //  $user1 =
            $user_mail = $form->get('email');


            $message = ( new \Swift_Message('Votre code de validation pour la plateforme'))
                ->setFrom("infostest@yolandadiva.com","SOUTENIR UN PROJET")
                ->setTo('gildas31@gmail.com')
                ->setBody($this->renderView('Email/code_validation.html.twig',[
                    'code'=>$code_validation,
                    'porteur_nom'=>$form->get('nom'),
                    'porteur_prenom'=>$form->get('prenom'),
                ]))
                ->setContentType('text/html')
            ;
            $mailer->send($message);
//            dump($mailer);
//            die();
            $inscription->setChargeId($charge->id);
            // $user->setPremium($charge->paid);
            $em->persist($inscription);
            $em->flush();

            return  $this->redirectToRoute('code-validation');


        }
        else {
//            dump($form->getErrors(true));
//            die();
        }


        return $this->render('@Acteur/Promotteurs/pre-inscription-feday.form.html.twig',[

           // 'form_inscription' => $InscriptionForm->createView(),
            'stripe_public_key' => $this->getParameter('stripe_public_key'),
            'form'=>$form->createView()
        ]);

    }
}