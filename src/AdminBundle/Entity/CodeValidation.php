<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CodeValidation
 *
 * @ORM\Table(name="code_validation")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\CodeValidationRepository")
 */
class CodeValidation
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set codeInscription
     *
     * @param string $codeInscription
     *
     * @return CodeValidation
     */
    public function setCodeInscription($codeInscription)
    {
        $this->codeInscription = $codeInscription;

        return $this;
    }

    /**
     * Get codeInscription
     *
     * @return string
     */
    public function getCodeInscription()
    {
        return $this->codeInscription;
    }

    /**
     * Set utilise
     *
     * @param boolean $utilise
     *
     * @return CodeValidation
     */
    public function setUtilise($utilise)
    {
        $this->utilise = $utilise;

        return $this;
    }

    /**
     * Get utilise
     *
     * @return bool
     */
    public function getUtilise()
    {
        return $this->utilise;
    }

    /**
     * Set dateValidation
     *
     * @param \DateTime $dateValidation
     *
     * @return CodeValidation
     */
    public function setDateValidation($dateValidation)
    {
        $this->dateValidation = $dateValidation;

        return $this;
    }

    /**
     * Get dateValidation
     *
     * @return \DateTime
     */
    public function getDateValidation()
    {
        return $this->dateValidation;
    }
}

