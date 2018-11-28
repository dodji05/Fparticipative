<?php

namespace ActeurBundle\Controller;

use ActeurBundle\Entity\InscriptionAttente;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


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
    public function preInscriptionAction(Request $request, SessionInterface $session)
    {


        $inscription = new  InscriptionAttente();
       // $session = new Session();
        $session->remove('enAttente');

        $form = $this->createForm('ActeurBundle\Form\Type\InscriptionAttenteType', $inscription);
        $form->handleRequest($request);


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
    public function preInscriptionPayAction (Request $request,SessionInterface $session){
       // $session = new Session();
        $sessionAttente = $session->get("enAttente");

//        $fedapay = $this->get('app.feday.client');
//        $fedapay->transactionFeday(10000);

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

    private function fedapayTransactionData($frais,$user)
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
            'description' => 'frais dinscription',
            'amount' => $frais,
            'currency' => ['iso' => 'XOF'],
            'callback_url' =>  $this->generateUrl('first_inscription_fedapay',array(null),0),//'https://soutenirunprojet.yo.fr/inscription-feday',//$this->generateUrl('first_inscription_fedapay') https://soutenirunprojet.yo.fr/http://localhost:8888/FparticipativeV3/web/app_dev.php/inscription-feday,
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



}

