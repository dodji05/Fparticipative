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
    private $nbProjetsEncours;
    private $nbProjetsBoucle;
    private $nbProjetsRealise;

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

    /**
     * @return mixed
     */
    public function getNbProjetsEncours()
    {
        return $this->nbProjetsEncours;
    }

    /**
     * @param mixed $nbProjetsEncours
     */
    public function setNbProjetsEncours($nbProjetsEncours)
    {
        //$nbProjetsEncours = $this->em->getRepository('AdminBundle:Projets')->projetsValides(5,"enFinancement");
        $this->nbProjetsEncours = $this->nbProjetsEncours();
    }

    /**
     * @return mixed
     */
    public function getNbProjetsBoucle()
    {
        return $this->nbProjetsBoucle;
    }

    /**
     * @param mixed $nbProjetsBoucle
     */
    public function setNbProjetsBoucle($nbProjetsBoucle  )
    {
        $this->nbProjetsBoucle = $this->nbProjetsBoucle();
    }

    /**
     * @return mixed
     */
    public function getNbProjetsRealise()
    {
        return $this->nbProjetsRealise;
    }

    /**
     * @param mixed $nbProjetsRealise
     */
    public function setNbProjetsRealise($nbProjetsRealise)
    {
        $this->nbProjetsRealise = $this->nbProjetsRealise();
    }

}