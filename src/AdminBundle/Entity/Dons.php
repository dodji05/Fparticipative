<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dons
 *
 * @ORM\Table(name="dons")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\DonsRepository")
 */
class Dons
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
     * @ORM\Column(name="montant", type="integer")
     */
    private $montant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateDons", type="datetime")
     */
    private $dateDons;

    /**
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Projets",inversedBy="dons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projet;

    /**
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Donateurs",inversedBy="dons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $donateur;



    public function __construct()
    {
        $this->dateDons = new \DateTime();
    }


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
     * Set montant
     *
     * @param integer $montant
     *
     * @return Dons
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return int
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set dateDons
     *
     * @param \DateTime $dateDons
     *
     * @return Dons
     */
    public function setDateDons($dateDons)
    {
        $this->dateDons = $dateDons;

        return $this;
    }

    /**
     * Get dateDons
     *
     * @return \DateTime
     */
    public function getDateDons()
    {
        return $this->dateDons;
    }

    /**
     * Set projet
     *
     * @param \AdminBundle\Entity\Projets $projet
     *
     * @return Dons
     */
    public function setProjet(\AdminBundle\Entity\Projets $projet)
    {
        $this->projet = $projet;

        return $this;
    }

    /**
     * Get projet
     *
     * @return \AdminBundle\Entity\Projets
     */
    public function getProjet()
    {
        return $this->projet;
    }

    /**
     * Set donateur
     *
     * @param \AdminBundle\Entity\Donateurs $donateur
     *
     * @return Dons
     */
    public function setDonateur(\AdminBundle\Entity\Donateurs $donateur)
    {
        $this->donateur = $donateur;

        return $this;
    }

    /**
     * Get donateur
     *
     * @return \AdminBundle\Entity\Donateurs
     */
    public function getDonateur()
    {
        return $this->donateur;
    }
}
