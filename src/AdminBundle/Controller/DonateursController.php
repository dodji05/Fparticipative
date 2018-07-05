<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
/**
 * Category controller.
 *
 * @Route("espace/administration/donateur")
 */
class DonateursController extends Controller
{
    /**
     * @Route("/",name="donateur_admin")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $donateurs = $em->getRepository('AdminBundle:Donateurs')->findAll();
        return $this->render('@Admin/Donateurs/Liste_donateur.html.twig', array('donateurs' => $donateurs));
    }

    /**
     * @Route("/fiche-donateur/{id}",name="donateur_fiche_admin")
     */
    public function ficheDonateurAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $donateur = $em->getRepository('AdminBundle:Donateurs')->findOneBy(array('id'=>$id));
        $projets_soutenus = $em->getRepository('AdminBundle:Dons')->NombreProjetsSoutenu($id) ;
        $details_dons =  $em->getRepository('AdminBundle:Dons')->findBy(['donateur'=>$donateur]);
        return $this->render('@Admin/Donateurs/Fiche_donateur.html.twig',
            array('donateur' => $donateur,
            'projets' => $projets_soutenus
            )
        );
    }
}
