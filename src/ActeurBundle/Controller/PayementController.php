<?php

namespace ActeurBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Porteur controller.
 *
 * @Route("/espace-donateur/payement")
 */
class PayementController extends Controller
{
    /**
     * @Route("/", name="premium_index")
     * @return Response
     */
    public function indexAction()
    {
        return $this->render(':premium:index.html.twig', [
            'payment_config' => $this->getParameter('payment'),
        ]);
    }
}
