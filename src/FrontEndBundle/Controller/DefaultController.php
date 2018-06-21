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

        $projets = $em->getRepository('AdminBundle:Projets')->projetsValides(5);
        return $this->render('FrontEndBundle:Default:accueil.html.twig',['projets'=>$projets]);
    }
    /**
     * @Route("/projets-realises",name="projets_realises")
     */
    public function projetsRealiseAction()
    {
        return $this->render('FrontEndBundle:Default:categories.html.twig',
            ["titreCategorie"=>"Projets realises",
            "descriptionCategoire"=>"Projets entierment par les dons sur la plateforme. Ces differeents ont ete complement realise"]);    }
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
                'projets'=>$projets]

            );
    }
    /**
     * @Route("/financements-ternimes",name="projets_finances")
     */
    public function projetsFinanceAction()
    {
        return $this->render('FrontEndBundle:Default:categories.html.twig',
            ["titreCategorie"=>"projets deja finances",
                "descriptionCategoire"=>"projets entierment par les dons sur la plateforme. Ces differeents ont ete complement realise"]);
    }
}
