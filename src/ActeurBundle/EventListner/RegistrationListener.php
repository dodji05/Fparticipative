<?php
/**
 * Created by PhpStorm.
 * User: rault
 * Date: 23/02/2018
 * Time: 10:54
 */

namespace ActeurBundle\EventListner;
use AdminBundle\Form\DonateursType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Model\UserManagerInterface;
use Proxies\__CG__\AdminBundle\Entity\CodeValidation;
use PUGX\MultiUserBundle\Model\UserDiscriminator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;

class RegistrationListener implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;
    private $em;
    private  $userManager;
    private  $formFactory;

    public function __construct(RouterInterface $router ,EntityManagerInterface $em, UserDiscriminator $userManager)
    {
        $this->router = $router;
        $this->em = $em;
        $this->userManager = $userManager;


    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_INITIALIZE => 'onRegistrationInitialize'
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $rolesArr = array('ROLE_PORTEUR_PROJET');

        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getForm()->getData();

       $origin = $event->getRequest()->attributes->get('_route');
       if ($origin =='donateur_register'){
           $user->setRoles(array('ROLE_INVESTISSEUR'));
       }
       elseif ($origin =='porteur_register'){
           // On recupere le code inscription dans la sesseion
           $session = new Session();
           $code = $session->get('codeInscription');

           // On verifie si le code est valide et non utilisee
           $repository2 = $this->em->getRepository('ActeurBundle:InscriptionAttente');
           $codeInscription = $repository2->findOneBy(array('codeInscription'=>$code,'utilise'=>0));
           if($codeInscription ){
               // code valide
               // on met l'attribut utilise a true et on persist
               $codeInscription->setUtilise(true);
               $this->em->persist($codeInscription);
               $this->em->flush();

               // on supprime la session codeInscription
               $session->remove('codeInscription');

           }
           $user->setRoles(array('ROLE_PORTEUR_PROJET'));
       }




    }
    public function onRegistrationInitialize(GetResponseUserEvent $event)
    {
       /* if (!$this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return;
        }*/
       //$user = $event->
     //   $form = $this->createForm(new DonateursType(), $user, ['role' => $this->getUser()->getRoles()]);


        $orign = $event->getRequest()->attributes->get('_route');
//        var_dump($orign);
//        die();
        if($orign !='donateur_register'){
            $session = new Session();
            $code = $session->get('codeInscription');
            if ($code === null){
                $url = $this->router->generate('code-validation');
                $response = new RedirectResponse($url);
                $event->setResponse($response);
            }
            else {
                $repository2 = $this->em->getRepository('ActeurBundle:InscriptionAttente');
                $attente = $repository2->findOneBy(array('codeInscription'=>$code,'utilise'=>0));
                $user = $event->getUser();
                $user->setNom($attente->getNom());
                $user->setPrenom($attente->getPrenom());
                $user->setTelephone($attente->getTelephone());
                $user->setEmail($attente->getEmail());
                $user->setEmailCanonical($attente->getEmail());
                // $this->userManager->
            }
        }

       // return $redirection;
    }
}