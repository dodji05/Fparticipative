<?php

namespace ActeurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InscriptionAttente
 *
 * @ORM\Table(name="inscription_attente")
 * @ORM\Entity(repositoryClass="ActeurBundle\Repository\InscriptionAttenteRepository")
 */
class InscriptionAttente
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
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="Prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="Charge_id", type="string", length=255)
     */
    private $chargeId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_inscription", type="datetime", length=255)
     */
    private $dateInscription;

    /**
     * @var string
     *
     * @ORM\Column(name="CodeInscription", type="string", length=255, unique=true)
     */
    private $codeInscription;

    /**
     * @var bool
     *
     * @ORM\Column(name="Utilise", type="boolean")
     */
    private $utilise;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateValidation", type="datetime", nullable=true)
     */
    private $dateValidation;

    /**
     * @var string
     *
     *
     */
    private $token;

    /**
     * InscriptionAttente constructor.
     * @param \DateTime $dateInscription
     * @param bool $utilise
     * @param \DateTime $dateValidation
     */
    public function __construct()
    {
        $this->dateInscription = new \DateTime();
        $this->utilise = false;
      //  $this->dateValidation = null;
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
     * @return InscriptionAttente
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
     * @return InscriptionAttente
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
     * Set email
     *
     * @param string $email
     *
     * @return InscriptionAttente
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set chargeId
     *
     * @param string $chargeId
     *
     * @return InscriptionAttente
     */
    public function setChargeId($chargeId)
    {
        $this->chargeId = $chargeId;

        return $this;
    }

    /**
     * Get chargeId
     *
     * @return string
     */
    public function getChargeId()
    {
        return $this->chargeId;
    }

    /**
     * @return \DateTime
     */
    public function getDateInscription()
    {
        return $this->dateInscription;
    }

    /**
     * @param \DateTime $dateInscription
     */
    public function setDateInscription($dateInscription)
    {
        $this->dateInscription = $dateInscription;
    }

    /**
     * @return string
     */
    public function getCodeInscription()
    {
        return $this->codeInscription;
    }

    /**
     * @param string $codeInscription
     */
    public function setCodeInscription($codeInscription)
    {
        $this->codeInscription = $codeInscription;
    }

    /**
     * @return bool
     */
    public function isUtilise()
    {
        return $this->utilise;
    }

    /**
     * @param bool $utilise
     */
    public function setUtilise($utilise)
    {
        $this->utilise = $utilise;
    }

    /**
     * @return \DateTime
     */
    public function getDateValidation()
    {
        return $this->dateValidation;
    }

    /**
     * @param \DateTime $dateValidation
     */
    public function setDateValidation($dateValidation)
    {
        $this->dateValidation = $dateValidation;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param string $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }


}

