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
     *@Route("/inscription", name="first_inscription")
     *
     */
    public function preInscriptionAction (Request $request){
        $defaultData = array('message' => 'Entre le code de validation');
        $codeForm = $this->createFormBuilder($defaultData)
            ->add('code', TextType::class,[
                'attr' => [
                    'class' => 'code_inscription',
                    'placehoder'=>'code d\'inscription'
//                    'onblur'=> 'remplissage(event)'
                ]
            ])
            ->getForm();



        if ($codeForm->isSubmitted() && $codeForm->isValid()) {
            $data = $codeForm->getData();
            $code = $data['code'];
            $doctrine = $this->getDoctrine();
            // $code_section = $doctrine->getRepository('ScomBundle:Sections')->
            $repository2 = $doctrine->getRepository('AdminBundle:CodeValidation');
            $codeInscription = $repository2->VerifieCode($code);
            if($codeInscription ){

                //Si le code d'inscription est valable , on enregistre dans la session
                $session = new Session();
                $session->remove('codeInscription');
                $session->set('codeInscription', $code);

                //puis on fait une redirection vers le formulaire d'inscripttion
                return  $this->redirectToRoute('fos_user_security_login');
            }
            else {
                //Dans le cas contraire on le maintient sur cette page

            }
        }
        $inscription =  new  InscriptionAttente();

        $form = $this->createForm('ActeurBundle\Form\Type\InscriptionAttenteType', $inscription);
        /** @var $userManager UserManagerInterface */
        $form->handleRequest($request);
        // $logger = new LoggerInterface();
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {

            $code_validation = md5(uniqid(mt_rand(), true));
            $inscription->setCodeInscription($code_validation);

            \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));
            // $config = array();
            $config = $this->getParameter('payment');

            try {

                $charge = \Stripe\Charge::create([
                    'amount' => "10000"/*20000$config['decimal'] ? $config['premium_amount'] * 100 : $config['premium_amount']*/,
                    'currency' => $config['currency'],
                    'description' => 'frais d\'inscription au projet',
                    //'customer'=> $user->getNom(),
                    'source' => $form->get('token')->getData(),
                    //'receipt_email' => 'gildas31@gmail.com'/*$user->getEmail()*/,
                ]);
            } catch (\Stripe\Error\Base $e) {
                //  $logger->error(sprintf('%s exception encountered when creating a premium payment: "%s"', get_class($e), $e->getMessage()), ['exception' => $e]);

                throw $e;
            }
            // Sauvegades du dons qui vient d'etre effectuee


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


        return $this->render('@Acteur/Promotteurs/pre-inscription.form.html.twig',[
            'form_code' => $codeForm->createView(),
           // 'form_inscription' => $InscriptionForm->createView(),
            'stripe_public_key' => $this->getParameter('stripe_public_key'),
            'form'=>$form->createView()
        ]);

    }
}