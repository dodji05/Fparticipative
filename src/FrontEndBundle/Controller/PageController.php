<?php

namespace FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PageController extends Controller
{
    /**
     * @Route("/qui-sommes-nous",name="aboutus")
     */
    public function aboutUsAction()
    {
        return $this->render('FrontEndBundle:Default/Partials:_AboutUs.html.twig');
    }
    /**
     * @Route("/comment-ca-marche",name="comment_marche")
     */
    public function commentCaMarcheAction()
    {
        return $this->render('FrontEndBundle:Default/Partials:_Comment_ca_marche.html.twig');
    }
}
