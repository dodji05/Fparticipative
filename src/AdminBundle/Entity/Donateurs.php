<?php

namespace AdminBundle\Entity;

use ActeurBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

/**
 * Donateurs
 *
 * @ORM\Table(name="donateurs")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\DonateursRepository")
 * @UniqueEntity(fields = "username", targetClass = "ActeurBundle\Entity\User", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "ActeurBundle\Entity\User", message="fos_user.email.already_used")
 */
class Donateurs extends User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="Prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @ORM\OneToMany(targetEntity="AdminBundle\Entity\Dons", mappedBy="donateur")
     */
    private $dons;

    /**
     * @ORM\OneToMany(targetEntity="AdminBundle\Entity\Remboursement", mappedBy="donateur")
     */
    private $remboursement;

    /**
     * @ORM\Column(name="charge_id", type="string", length=255, nullable=true)
     */
    protected $chargeId;

    /**
     * @return mixed
     */
    public function getChargeId()
    {
        return $this->chargeId;
    }

    /**
     * @param mixed $chargeId
     */
    public function setChargeId($chargeId)
    {
        $this->chargeId = $chargeId;
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Donateurs
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Donateurs
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Donateurs
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Donateurs
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Donateurs
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dons = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add don
     *
     * @param \AdminBundle\Entity\Dons $don
     *
     * @return Donateurs
     */
    public function addDon(\AdminBundle\Entity\Dons $don)
    {
        $this->dons[] = $don;

        return $this;
    }

    /**
     * Remove don
     *
     * @param \AdminBundle\Entity\Dons $don
     */
    public function removeDon(\AdminBundle\Entity\Dons $don)
    {
        $this->dons->removeElement($don);
    }

    /**
     * Get dons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDons()
    {
        return $this->dons;
    }

    /**
     * Add remboursement
     *
     * @param \AdminBundle\Entity\Remboursement $remboursement
     *
     * @return Donateurs
     */
    public function addRemboursement(\AdminBundle\Entity\Remboursement $remboursement)
    {
        $this->remboursement[] = $remboursement;

        return $this;
    }

    /**
     * Remove remboursement
     *
     * @param \AdminBundle\Entity\Remboursement $remboursement
     */
    public function removeRemboursement(\AdminBundle\Entity\Remboursement $remboursement)
    {
        $this->remboursement->removeElement($remboursement);
    }

    /**
     * Get remboursement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRemboursement()
    {
        return $this->remboursement;
    }
}
