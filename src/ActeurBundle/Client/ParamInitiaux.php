<?php
/**
 * Created by PhpStorm.
 * User: dodji
 * Date: 29/08/18
 * Time: 01:38
 */

namespace ActeurBundle\Client;


use Doctrine\ORM\EntityManagerInterface;

class ParamInitiaux
{

    private $em;

    public function __construct( EntityManagerInterface $em)
    {
        $this->em = $em;

    }

    public function nbProjetsEncours(){
        $nb = $this->em->getRepository('AdminBundle:Projets')->projetsValides(5,"enFinancement");
        return sizeof($nb);
    }
    public function nbProjetsBoucle(){
        $nb = $this->em->getRepository('AdminBundle:Projets')->projetsValides(5,"FinacementBoucle");
        return sizeof($nb);
    }
    public function nbProjetsRealise(){
        $nb = $this->em->getRepository('AdminBundle:Projets')->projetsValides(5,"ProjetRealise");
        return sizeof($nb);
    }
}