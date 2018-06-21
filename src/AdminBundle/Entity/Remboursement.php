<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dons
 *
 * @ORM\Table(name="remboursement")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\RemboursementRepository")
 */
class Remboursement
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
     * @ORM\Column(name="DateRemboursement", type="datetime")
     */
    private $dateRemboursement;

    /**
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Projets",inversedBy="remboursement")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projet;

    /**
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Donateurs",inversedBy="remboursement")
     * @ORM\JoinColumn(nullable=false)
     */
    private $donateur;



    public function __construct()
    {
        $this->dateRemboursement = new \DateTime();
    }




    /**
     * Get id
     *
     * @return integer
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
     * @return Remboursement
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return integer
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set dateRemboursement
     *
     * @param \DateTime $dateRemboursement
     *
     * @return Remboursement
     */
    public function setDateRemboursement($dateRemboursement)
    {
        $this->dateRemboursement = $dateRemboursement;

        return $this;
    }

    /**
     * Get dateRemboursement
     *
     * @return \DateTime
     */
    public function getDateRemboursement()
    {
        return $this->dateRemboursement;
    }

    /**
     * Set projet
     *
     * @param \AdminBundle\Entity\Projets $projet
     *
     * @return Remboursement
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
     * @return Remboursement
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
