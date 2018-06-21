<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\media;
use AdminBundle\Entity\Projets;
use AdminBundle\Form\ProjetValideType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Porteur controller.
 *
 * @Route("espace/administration/projets")
 */
class ProjetsController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }
    /**
     * Creates a new porteur entity.
     *
     * @Route("/new", name="projet_new")
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
        $form = $this->createForm('AdminBundle\Form\ProjetsType', $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();


            foreach ($originalMedia as $media){
                if(false === $projet->getMedia()->contains($media)){
                  //  $media->setProjetsMedia($projet->getId());
                    $em->persist($media);
                }
            }
            $em->persist($projet);
            $em->flush();

            return $this->redirectToRoute('projet_liste', array('id' => $projet->getId()));
        }

        return $this->render('@Admin/projets/nouveau.html.twig', array(
            'projet' => $projet,
            'form' => $form->createView(),
        ));
    }
    /**
     * liste tous les projets.
     *
     * @Route("/liste", name="projet_liste")
     * @Method({"GET", "POST"})
     */
    public function lesProjetsAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $projets = $em->getRepository('AdminBundle:Projets')->findAll();

        return $this->render('@Admin/projets/touslesprojets.html.twig', array(
            'projets' => $projets,
        ));
    }

    /**
     * liste tous les projets.
     *
     * @Route("/validation/{id}", name="projet_validation")
     * @Method({"GET", "POST"})
     */

    public function vaildeProjetsAction(Request $request,$id){

        $repository =$this->getDoctrine()->getRepository('AdminBundle:Projets');
        $projet = $repository->find($id);

        $form = $this->createForm(ProjetValideType::class,  $projet);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $projet->setSelectionne(true);
            $projet->setDateValidation(new \DateTime());


            $em->persist($projet);
            $em->flush();


            $this->addFlash('infos', "Modification effectuée avec succés");
            return $this->redirectToRoute('projet_liste');

        }
        return $this->render('@Admin/projets/validationprojet.html.twig', ['form' => $form->createView(),'projet'=>$projet]);

        }

    /**
     * liste tous les projets.
     *
     * @Route("/fiche-projet/{id}", name="le_projet_admin ")
     * @Method({"GET", "POST"})
     */

    public function leProjetAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();

        $projets = $em->getRepository('AdminBundle:Projets')->findOneBy(array('id'=>$id));

        return $this->render('@Admin/projets/projet.html.twig', ['projet'=>$projets]);

    }

    /**
     * liste tous les projets.
     *
     * @Route("/projet_valide", name="projet_valide")
     * @Method({"GET", "POST"})
     */
    public function projetsEnfinancementAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $projets = $em->getRepository('AdminBundle:Projets')->tousLesProjetsValides();

        return $this->render('@Admin/projets/projets_en_financement.html.twig', ['projets'=>$projets]);

    }
}
