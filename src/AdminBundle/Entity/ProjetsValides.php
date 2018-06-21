<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjetsValides
 *
 * @ORM\Table(name="projets_valides")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\ProjetsValidesRepository")
 */
class ProjetsValides
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="objectif", type="integer")
     */
    private $objectif;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateLimite", type="date")
     */
    private $dateLimite;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set objectif
     *
     * @param integer $objectif
     *
     * @return ProjetsValides
     */
    public function setObjectif($objectif)
    {
        $this->objectif = $objectif;

        return $this;
    }

    /**
     * Get objectif
     *
     * @return int
     */
    public function getObjectif()
    {
        return $this->objectif;
    }

    /**
     * Set dateLimite
     *
     * @param \DateTime $dateLimite
     *
     * @return ProjetsValides
     */
    public function setDateLimite($dateLimite)
    {
        $this->dateLimite = $dateLimite;

        return $this;
    }

    /**
     * Get dateLimite
     *
     * @return \DateTime
     */
    public function getDateLimite()
    {
        return $this->dateLimite;
    }
}
