<?php

namespace ActeurBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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

        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager UserManagerInterface */
        //$userManager = $this->get('fos_user.user_manager');
        $form = $formFactory->createForm()
            //->
            ->add('token', HiddenType::class, [
                'constraints' => [new NotBlank()],
            ])
            ;


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

        $form->handleRequest($request);
        // $logger = new LoggerInterface();


        return $this->render('@Acteur/Promotteurs/pre-inscription.form.html.twig',[
            'form_code' => $codeForm->createView(),
           // 'form_inscription' => $InscriptionForm->createView(),
            'stripe_public_key' => $this->getParameter('stripe_public_key'),
            'form'=>$form->createView()
        ]);

    }
}