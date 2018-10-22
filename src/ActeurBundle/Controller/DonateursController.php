<?php

namespace ActeurBundle\Controller;
use AdminBundle\Entity\Dons;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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

        $repos = $em->getRepository('AdminBundle:Projets');
        $projet = $repos->findOneBy(array('id'=>$id));


        $form = $this->get('form.factory')
            ->createNamedBuilder('payment-form')
            ->add('token', HiddenType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('montant',IntegerType::class)
            ->add('submit', SubmitType::class)
            ->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
           // $logger = new LoggerInterface();
            if ($form->isValid()) {

                $token =  $form->get('token')->getData();
                \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));
                $stripeClient = $this->get('app.stripe.client');
                $config = $this->getParameter('payment');
                /** @var User $user */

                $user = $this->getUser();
                if (!$user->getStripeCustomerId()) {
                    $stripeClient->createCustomer($user, $token);
                } else {
                    $stripeClient->updateCustomerCard($user, $token);
                }
                $stripeClient->createInvoiceItem(
                    $form->get('montant')->getData(),
                    $user,
                    "SOUTIENT AU PROJET ".$projet->getNomProjet(),
                    $config['currency']
                );
                // guarantee it charges *right* now
                $stripeClient->createInvoice($user, true);

                $dons =  new Dons();
                $dateDons = new \DateTime();
                $dons->setDonateur($user);
                $dons->setDateDons($dateDons);
                $dons->setMontant($form->get('montant')->getData());
                $dons->setProjet($projet);
                // $dons->setDonateur($this->getUser());
                $em->persist($dons);

                // $user->setChargeId($charge->id);
                // $user->setPremium($charge->paid);
                $em->persist($user);
                $em->flush();

                //Envoi de mail
                // 1- au donateur
                // 2- au porteur de projet
                // 3- A l'association

                return $this->redirectToRoute('facture_dons',['id'=>$dons->getId()]);
            }


//            if ($form->isValid()) {
//                \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));
//               // $config = array();
//                $config = $this->getParameter('payment');
//
//                try {
//
//
//                    $charge = \Stripe\Charge::create([
//                        'amount' => $form->get('montant')->getData()/*20000$config['decimal'] ? $config['premium_amount'] * 100 : $config['premium_amount']*/,
//                        'currency' => $config['currency'],
//                        'description' => $projet->getNomProjet(),
//                        //'customer'=> $user->getNom(),
//                        'source' => $form->get('token')->getData(),
//                        //'receipt_email' => 'gildas31@gmail.com'/*$user->getEmail()*/,
//                    ]);
//                } catch (\Stripe\Error\Base $e) {
//                  //  $logger->error(sprintf('%s exception encountered when creating a premium payment: "%s"', get_class($e), $e->getMessage()), ['exception' => $e]);
//
//                    throw $e;
//                }
//                // Sauvegades du dons qui vient d'etre effectuee

//            }
        };

        $projet = $em->getRepository('AdminBundle:Projets')->findOneBy(array('id'=>$id, 'selectionne'=>1));
        return $this->render('@Acteur/Donateurs/soutenir.html.twig', array(
            'projet' => $projet,
            'form' => $form->createView(),
            'stripe_public_key' => $this->getParameter('stripe_public_key'),));
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
}
