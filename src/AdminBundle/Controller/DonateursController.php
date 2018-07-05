<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DonateursController extends Controller
{
    public function indexAction($name)
    {
        $em = $this->getDoctrine()->getManager();

        $donateur = $em->getRepository('AdminBundle:Donateurs')->findAll();
        return $this->render('', array('name' => $name));
    }
}
