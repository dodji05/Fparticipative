<?php

namespace ActeurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller
{
    /**
* @Route("/inscription/{code}",name="codeverification",options={"expose"=true})
*/
    public function verifieCodeAction(Request $request, $code)
    {
        $doctrine = $this->getDoctrine();
        // $code_section = $doctrine->getRepository('ScomBundle:Sections')->
        $repository2 = $doctrine->getRepository('AdminBundle:CodeValidation');
        $codeInscription = $repository2->VerifieCode($code);
        if($codeInscription ){$response = array("success" => true,

        );}
        else {$response = array("success" => false,);}
        return new Response(json_encode($response));

    }
    }
