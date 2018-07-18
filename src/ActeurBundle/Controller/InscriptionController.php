<?php

namespace ActeurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InscriptionController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }
}
