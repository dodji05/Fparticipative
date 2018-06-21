<?php

namespace ActeurBundle\Controller;

use AdminBundle\Entity\Donateurs;
use AdminBundle\Entity\Dons;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use ActeurBundle\Client\StripeClient;
use Psr\Log\LoggerInterface;
/**
 * Porteur controller.
 *
 * @Route("/espace-donateur")
 */

class DonateursController extends Controller
{
    /**
     * @Route("/",name="donateur_admin")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        // $logger = new LoggerInterface();
        //  $redirect = $this->generateUrl('donateur_admin');;
        $fond = $em->getRepository('AdminBundle:Dons');
        $fond = $fond->findBy(array('donateur'=>$this->getUser()));

        $projets =  $em->getRepository('AdminBundle:Projets')->projetsValides(10);
       // $user = $em->getRepository('AdminBundle:Donateurs')->findOneBy(array('id'=>4));

        return $this->render('@Acteur/Donateurs/index.html.twig', array(
            'fonds'=>$fond,
            'projets'=>$projets,
        ));
    }
    /**
     * @Route("/projets-finances",name="donateur_projets_finances")
     */
    public function mesProjetsFinanacesAction() {
        $em = $this->getDoctrine()->getManager();
        $projets =  $em->getRepository('AdminBundle:Dons')->projetsFinances($this->getUser());

       // var_dump($projets);die();

        return $this->render('@Acteur/Donateurs/projets_finances.html.twig' ,array(
            //'fonds'=>$fond,
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
       // $logger = new LoggerInterface();
      //  $redirect = $this->generateUrl('donateur_admin');;
        $repos = $em->getRepository('AdminBundle:Projets');
        $projet = $repos->findOneBy(array('id'=>$id));
        $user = $em->getRepository('AdminBundle:Donateurs')->findOneBy(array('id'=>4));


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
                \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));
               // $config = array();
                $config = $this->getParameter('payment');

                try {


                    $charge = \Stripe\Charge::create([
                        'amount' => $form->get('montant')->getData()/*20000$config['decimal'] ? $config['premium_amount'] * 100 : $config['premium_amount']*/,
                        'currency' => $config['currency'],
                        'description' => $projet->getNomProjet(),
                        //'customer'=> $user->getNom(),
                        'source' => $form->get('token')->getData(),
                        //'receipt_email' => 'gildas31@gmail.com'/*$user->getEmail()*/,
                    ]);
                } catch (\Stripe\Error\Base $e) {
                  //  $logger->error(sprintf('%s exception encountered when creating a premium payment: "%s"', get_class($e), $e->getMessage()), ['exception' => $e]);

                    throw $e;
                }
                // Sauvegades du dons qui vient d'etre effectuee
                $dons =  new Dons();
                $dateDons = new \DateTime();
                $dons->setDonateur($user);
                $dons->setDateDons($dateDons);
                $dons->setMontant($form->get('montant')->getData());
                $dons->setProjet($projet);
               // $dons->setDonateur($this->getUser());
                $em->persist($dons);

                $user->setChargeId($charge->id);
                // $user->setPremium($charge->paid);
                $em->persist($user);
                $em->flush();

                return $this->render('ActeurBundle:Donateurs:Facture-don.html.twig', array(
                    'projet' => $projet->getNomProjet(),
                    'Montant'=>$form->get('montant')->getData(),
                    'DateD'=>$dateDons
                ));
            }
        }

        $projet = $em->getRepository('AdminBundle:Projets')->findOneBy(array('id'=>$id, 'selectionne'=>1));
        return $this->render('@Acteur/Donateurs/soutenir.html.twig', array(
            'projet' => $projet,
            'form' => $form->createView(),
            'stripe_public_key' => $this->getParameter('stripe_public_key'),));
    }
}
