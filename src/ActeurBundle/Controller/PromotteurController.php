<?php

namespace ActeurBundle\Controller;

use AdminBundle\Entity\media;
use AdminBundle\Entity\Projets;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Porteur controller.
 *
 * @Route("/espace-promoteur")
 */

class PromotteurController extends Controller
{
    /**
     * @Route("/",name="porteur_admin")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        //totat des sommes recoltées au cours des 7 derniers jours
        $montant_recolte = $em->getRepository('AdminBundle:Dons')->septDerniersDonsMotant($this->getUser());
        // on recupere le ou les projets du promoteur
        $projets = $em->getRepository('AdminBundle:Dons')->dixDerniersDons($this->getUser());

        return $this->render('ActeurBundle:Promotteurs:index.html.twig', array('projets' =>  $projets,
            'montant_recolte'=>$montant_recolte));
    }

    /**
     * Creates a new porteur entity.
     *
     * @Route("/projet/new", name="porteur_projet_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $projet = new Projets();
        $originalMedia = new ArrayCollection();

        for ($i=0;$i<2;$i++){
            $media = new media();
            $media->setProjetsMedia($projet);
            $originalMedia->add($media);

            $projet->addMedia($media);

        }
        $form = $this->createForm('ActeurBundle\Form\Type\ProjetType', $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();


            foreach ($originalMedia as $media){
                if(false === $projet->getMedia()->contains($media)){
                    //  $media->setProjetsMedia($projet->getId());
                    $em->persist($media);
                }
            }
            $projet->setPorteur($this->getUser());
            $em->persist($projet);
            $em->flush();
            $this->addFlash('success','Feliciation !!! Votre projet soumis avec succes');

            return $this->redirectToRoute('porteur_projet_new_confirm', array('id' => $projet->getId()));
        }

        return $this->render('@Acteur/Promotteurs/nouveau_projet.html.twig', array(
            'projet' => $projet,
            'form' => $form->createView(),
            'type'=>'nouveau'
        ));
    }

    /**
     * Creates a new porteur entity.
     *
     * @Route("/projet/new/{id}", name="porteur_projet_new_confirm")
     * @Method({"GET", "POST"})
     */
    public function confirnActon (Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Projets::class);
        $projet = $rep->findOneBy(array('id'=>$id));

        return $this->render('@Acteur/Promotteurs/nouveau_projet.html.twig', array(
            'projet' => $projet,
            'type' => 'confirm'

        ));

    }

    /**
     * Creates a new porteur entity.
     *
     * @Route("/mes-projets", name="mes_projets")
     *
     */
    public function mesProjetsAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository(Projets::class);
        $mes_projets = $rep->findBy(array('porteur'=>$this->getUser()));

        return $this->render('@Acteur/Promotteurs/mes_projets.html.twig', array(
            'projets' => $mes_projets,

        ));

    }
    public function preInscriptionAction (Request $request){
        return $this->renderView('@Acteur/Promotteurs/pre-inscription.form.html.twig');

    }
}
