<?php

namespace FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProjetsController extends Controller
{
    /**
     * @Route("/le-projet/{id}",name="le_projet")
     */
    public function leProjetAction($id)

    {
        $em = $this->getDoctrine()->getManager();

        $projet = $em->getRepository('AdminBundle:Projets')->findOneBy(array('id'=>$id, 'selectionne'=>1));

        $name="jean";
        return $this->render('@FrontEnd/Default/Projets/projet.html.twig', array('projet' =>  $projet));
    }
}
