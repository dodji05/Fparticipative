<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/espace/administration",name="homepage_admin")
     */
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:TB.html.twig');
    }
}
