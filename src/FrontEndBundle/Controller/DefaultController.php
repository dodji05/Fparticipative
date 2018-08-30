<?php

namespace FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/",name="accueil")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
   // $nbfinance = $em->getRepository('')

        $projets = $em->getRepository('AdminBundle:Projets')->projetsValides(5,"enFinancement");
        $projetsBoucles = $em->getRepository('AdminBundle:Projets')->projetsValides(5,"FinacementBoucle");

        return $this->render('FrontEndBundle:Default:accueil.html.twig',[
            'projets'=>$projets,
            'projetsBoucles'=>$projetsBoucles

        ]);
    }
    /**
     * @Route("/projets-realises",name="projets_realises")
     */
    public function projetsRealiseAction()
    {
        $em = $this->getDoctrine()->getManager();
        $projets = $em->getRepository('AdminBundle:Projets')->projetsValides(5,"FinacementBoucle");
        return $this->render('FrontEndBundle:Default:categories.html.twig',
            ["titreCategorie"=>"Projets realises",
            "descriptionCategoire"=>"Projets entièrement finances et realise par les dons sur la plateforme. Ces différents ont été complément finalisés et realises",
                'projets'=>$projets,]);    }
    /**
     * @Route("/projets-en-cours",name="projets_en_cours")
     */
    public function projetsEcoursAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projets = $em->getRepository('AdminBundle:Projets')->tousLesProjetsValides();

        return $this->render('FrontEndBundle:Default:categories.html.twig',
            ["titreCategorie"=>"Projets en cours de financement",
                "descriptionCategoire"=>"Évaluez les projets et contribuez à leur financement",
                'projets'=>$projets,
                ]

            );
    }
    /**
     * @Route("/financements-ternimes",name="projets_finances")
     */
    public function projetsFinanceAction()
    {
        $em = $this->getDoctrine()->getManager();
        $projets = $em->getRepository('AdminBundle:Projets')->projetsValides(5,"FinacementBoucle");
        return $this->render('FrontEndBundle:Default:categories.html.twig',
            ["titreCategorie"=>"Projets déjà financés",
                "descriptionCategoire"=>"Projets entièrement finance par les dons sur la plateforme. Ces différents ont été complément finalisés",
                'projets'=>$projets,]);
    }
}
