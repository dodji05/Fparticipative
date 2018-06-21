<?php

namespace ActeurBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class RegistrationDonateurController extends Controller
{
    /**
     * Creates a new porteur entity.
     *
     * @Route("/register/donateur", name="donateur_register")
     *
     */

    public function registerAction()
    {
        return $this->container
            ->get('pugx_multi_user.registration_manager')
            ->register('AdminBundle\Entity\Donateurs');
    }
}