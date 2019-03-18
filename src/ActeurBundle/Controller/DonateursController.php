<?php

namespace ActeurBundle\Controller;
use AdminBundle\Entity\Dons;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\NotBlank;
use ActeurBundle\Client\StripeClient;

/**
 * Donateur controller.
 *
 * @Route("/espace-donateur")
 */

class DonateursController extends Controller
{
    /**
     * @Route("/",name="donateur_admin_TB")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        // $logger = new LoggerInterface();
        //  $redirect = $this->generateUrl('donateur_admin');;
        $fond = $em->getRepository('AdminBundle:Dons');
        $fond = $fond->findBy(array('donateur'=>$this->getUser()));

        $projets =  $em->getRepository('AdminBundle:Projets')->projetsValides(10,"enFinancement");
        // $user = $em->getRepository('AdminBundle:Donateurs')->findOneBy(array('id'=>4));

        return $this->render('@Acteur/Donateurs/index.html.twig', array(
            'fonds'=>$fond,
            'projets'=>$projets,
        ));
    }
    /**
     * @Route("/mes-projets-finances",name="donateur_projets_finances")
     */
    public function mesProjetsFinanacesAction() {
        $em = $this->getDoctrine()->getManager();
        $projets =  $em->getRepository('AdminBundle:Dons')->projetsFinances($this->getUser());

        // var_dump($projets);die();$projets = $em->getRepository('AdminBundle:Projets')->tousLesProjetsValides();

        return $this->render('@Acteur/Donateurs/projets_finances.html.twig' ,array(
            'type'=>'FINANCE',
            'projets'=>$projets,
        ));
    }
    /**
     * @Route("/les-projets-encours",name="donateur_les_projets")
     */
    public function mesProjetsEncoursAction() {
        $em = $this->getDoctrine()->getManager();
        $projets =   $em->getRepository('AdminBundle:Projets')->tousLesProjetsValides();

        // var_dump($projets);die();$projets =;

        return $this->render('@Acteur/Donateurs/projets_finances.html.twig' ,array(
            'type'=>'TOUS',
            'projets'=>$projets,
        ));
    }

    /**
     * @Route("/historique-investissement",name="don_historique")
     */
    public function historiqueInvestAction()
    {
        $em = $this->getDoctrine()->getManager();
        // $logger = new LoggerInterface();
        //  $redirect = $this->generateUrl('donateur_admin');;
        $fond = $em->getRepository('AdminBundle:Dons');
        $fond = $fond->findBy(array('donateur'=>$this->getUser()));

        $projets =  $em->getRepository('AdminBundle:Projets')->projetsValides(10);
        // $user = $em->getRepository('AdminBundle:Donateurs')->findOneBy(array('id'=>4));

        return $this->render('@Acteur/Donateurs/Historique_invest.html.twig', array(
            'fonds'=>$fond,

        ));
    }

    /**
     * @Route("/projet/{id}",name="payement_admin")
     */
    public function VoteProjetAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $projet = $em->getRepository('AdminBundle:Projets')->findOneBy(array('id'=>$id, 'selectionne'=>1));
        $form = $this->get('form.factory')
            ->createNamedBuilder('payment-form')

            ->add('montant',IntegerType::class,[
                'attr' => [
                    'placeholder' =>'Veuillez entre le montant de votre participation'
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label'  => "Je participe au projet",
                'attr' => [
                    'class' =>'btn btn-outline-info'
                ]
            ])
            ->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            // $logger = new LoggerInterface();
            if ($form->isValid()) {
                $user = $this->getUser();
                $montant =  $form->get('montant')->getData();

                $session->set('donation_montant',$montant);

                \FedaPay\FedaPay::setApiKey($this->getParameter('feday_secret_key'));


                try {
                    $transaction = \FedaPay\Transaction::create(
                        $this->fedapayTransactionData($montant, $user, $projet)
                    );
                    $token = $transaction->generateToken();
                    return $this->redirect($token->url);
                }
                catch(\Exception $e) {
                    $this->addFlash(
                        'danger',
                        $e->getMessage()
//                       'Votre participation au projet : '.$projet->getNomProjet().' pour un montant de :'.$montant.' a été pris en compte'
                    //  $this->generateUrl('first_inscription_fedapay'.$e->getMessage(),array(null),0)
                    );
                    return $this->redirectToRoute('payement_admin',['id'=>$id]);
                }



            }



        };
        $session->remove('donation_montant');

        return $this->render('@Acteur/Donateurs/soutenir.html.twig', array(
                'projet' => $projet,
                'form' => $form->createView(),
                'stripe_public_key' => $this->getParameter('feday_public_key'),
            )
        );
    }

    /**
     * @Route("/confirmation-dons/{id}",name="facture_dons")
     */
    public function confirmationAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $leDon = $em->getRepository('AdminBundle:Dons')->findOneBy(array('id'=>$id,'donateur'=>$this->getUser()));



//envoi de mail
        $smtpkalo  = new \Swift_SmtpTransport('mail07.lwspanel.com',25);
        $smtpkalo->setUsername('infostest@yolandadiva.com')
            ->setPassword('Henry_1024');
        $mailer = new \Swift_Mailer($smtpkalo);



        //Envoi de mail
        // 1- au donateur
        $message_donateur = (new \Swift_Message('[PARTICIPATION BIEN RECUE] Pour le projet '))
            ->setFrom($this->getParameter('mailer_user'))
            ->setTo($this->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'Email/Dons/notification_donateur.html.twig',
                    array('don' => $leDon)
                ),
                'text/html'
            );
        $mailer->send($message_donateur);
        // 2- au porteur de projet

        $message_porteur = (new \Swift_Message('[NOUVELLE PARTICIPATION] pour votre projet '))
            ->setFrom($this->getParameter('mailer_user'))
            ->setTo($leDon->getProjet()->getPorteur()->getEmail())
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'Email/Dons/notification_promoteur.html.twig',
                    array('don' => $leDon)
                ),
                'text/html'
            );
        $mailer->send($message_porteur);
        // 3- A l'association

        $message_association = (new \Swift_Message('[NOUVELLE PARTICIPATION] pour le projet '))
            ->setFrom($this->getParameter('mailer_user'))
            ->setTo($this->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/no
                    'Email/Dons/notification_co.html.twig',
                    array('don' => $leDon)
                ),
                'text/html'
            );
        $mailer->send($message_association);

        return $this->render('ActeurBundle:Donateurs:Facture-don.html.twig', array(
            'projet' => $leDon,

        ));
    }

    private function fedapayTransactionData($frais, $user, $projet)
    {
        $customer_data = [
            'firstname' => $user->getNom(),
            'lastname' => $user->getPrenom(),
            'email' =>$user->getEmail(),
            'phone_number' => [
                'number'  => $user->getTelephone(),
                'country' => 'bj'
            ]
        ];
        return [
            'description' => 'Participation au projet',
            'amount' => $frais,
            'currency' => ['iso' => 'XOF'],
            'callback_url' =>  $this->generateUrl('donation_fedapay',['projet'=>$projet->getId()],0),//'https://soutenirunprojet.yo.fr/inscription-feday',//$this->generateUrl('first_inscription_fedapay') https://soutenirunprojet.yo.fr/http://localhost:8888/FparticipativeV3/web/app_dev.php/inscription-feday,
            'customer' => $customer_data
        ];
    }

/*
*
*@Route("/inscription-payement", name="first_inscription_payement")
*
*/
    public function preInscriptionPayAction (Request $request,SessionInterface $session){
        // $session = new Session();
        $sessionAttente = $session->get("enAttente");

//        $fedapay = $this->get('app.feday.client');
//        $fedapay->transactionFeday(10000);
        //  \FedaPay\FedaPay::setEnvironment():

        \FedaPay\FedaPay::setApiKey($this->getParameter('feday_secret_key'));


        try {
            $transaction = \FedaPay\Transaction::create(
                $this->fedapayTransactionData($this->getParameter('frais_adhesion'), $sessionAttente)
            );
            $token = $transaction->generateToken();
            return $this->redirect($token->url);
        } catch(\Exception $e) {
            $this->addFlash(
                'notice',
                $this->generateUrl('first_inscription_fedapay'.$e->getMessage(),array(null),0)
            );
            return $this->redirectToRoute('first_inscription');
        }

    }
    /**
     *
     *@Route("/donation-feday/{projet}", name="donation_fedapay")
     *
     */


    public function callbackAction(Request $request,$projet)
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
                    $montant = $session->get('donation_montant');
                    $user = $this->getUser();
                    $em = $this->getDoctrine()->getManager();

                    $repos = $em->getRepository('AdminBundle:Projets');
                    $projet = $repos->findOneBy(array('id'=>$projet));

                    $dons =  new Dons();
                    $dateDons = new \DateTime();
                    $dons->setDonateur($user);
                    $dons->setDateDons($dateDons);
                    $dons->setMontant($montant);
                    $dons->setProjet($projet);
                    // $dons->setDonateur($this->getUser());
                    $em->persist($dons);


                    $em->persist($user);
                    $em->flush();

                    //Envoi de mail
                    // 1- au donateur
                    // 2- au porteur de projet
                    // 3- A l'association


                    $this->addFlash(
                        'success',
                        'Felicitation!!!! votre transaction s est effectuee avec succes. Veuillez entre le code reçu par sms et par mail dans le formulaire ci-dessous  !!!!!'
                    );
                    return $this->redirectToRoute('facture_dons',['id'=>$dons->getId()]);
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
                $e->getMessage()
            );
            return $this->redirectToRoute('first_inscription');

        }
        return $this->redirectToRoute('first_inscription');
    }

}
