<?php

namespace ActeurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/iopuy",name="porteur_admin_")
     */
    public function indexAction()
    {
        return $this->render('ActeurBundle:Promotteurs:index.html.twig');
    }

}
