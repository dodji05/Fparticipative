<?php

namespace ActeurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class CodeVerificationController extends Controller
{
/**
* Porteur controller.
*
* @Route("/register/code-validation",name="code-validation")
*/
    public function indexAction(Request $request)
    {
        $defaultData = array('message' => 'Entre le code de validation');
        $form = $this->createFormBuilder($defaultData)
            ->add('code', TextType::class,[
                'attr' => [
                    'class' => 'code_inscription',
                    'placehoder'=>'code d\'inscription'
//                    'onblur'=> 'remplissage(event)'
                ]
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
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
               return  $this->redirectToRoute('fos_user_registration_register');
            }
            else {
                //Dans le cas contraire on le maintient sur cette page

            }
        }
        return $this->render('@Acteur/Promotteurs/Partials/code_verification.htm.twig', array(
            'form' => $form->createView(),
        ));
    }
}
