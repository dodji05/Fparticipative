<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Projets
 *
 * @ORM\Table(name="projets")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\ProjetsRepository")
 * @Vich\Uploadable
 */
class Projets
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
     * @ORM\Column(name="nomProjet", type="string", length=255)
     */
    private $nomProjet;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionProjet", type="text", nullable=true)
     */
    private $descriptionProjet;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionCourteProjet", type="text", nullable=true)
     */
    private $descriptionCourteProjet;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @Vich\UploadableField(mapping="admin_images", fileNameProperty="docPlanAffaire")
     * @Assert\File(mimeTypes = {"application/pdf"})
     * @var File
     */
    private $planFile;

    /**
     * @Vich\UploadableField(mapping="admin_images", fileNameProperty="logo")
     * @var File
     */
    private $imageFile;
    /**
     * @var boolean
     *
     * @ORM\Column(name="selectionne", type="boolean")
     */
    private $selectionne;
    /**
     * @var int
     *
     * @ORM\Column(name="objectif", type="integer",nullable=true)
     */
    private $objectif;

    /**
     * @var int
     *
     * @ORM\Column(name="budgetpropose", type="integer",nullable=true)
     */
    private $budgetPropose;




    /**
     * @var string
     *
     * @ORM\Column(name="docPlanAffaire", type="string",length=255,nullable=true)
     */
    private $docPlanAffaire;



    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateLimite", type="date",nullable=true)
     */
    private $dateLimite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateValidation", type="date",nullable=true)
     */
    private $dateValidation;

    /**
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Categories",inversedBy="projet")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Porteur",inversedBy="projet_porteur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $porteur;

    /**
     * @ORM\OneToMany(targetEntity="AdminBundle\Entity\media", mappedBy="projets_media",cascade={"persist"})
     */
    private $media;

    /**
     * @ORM\OneToMany(targetEntity="AdminBundle\Entity\Dons", mappedBy="projet")
     */
    private $dons;

    /**
     * @ORM\OneToMany(targetEntity="AdminBundle\Entity\Remboursement", mappedBy="projet")
     */
    private $remboursement;


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
     * Set nomProjet
     *
     * @param string $nomProjet
     *
     * @return Projets
     */
    public function setNomProjet($nomProjet)
    {
        $this->nomProjet = $nomProjet;

        return $this;
    }

    /**
     * Get nomProjet
     *
     * @return string
     */
    public function getNomProjet()
    {
        return $this->nomProjet;
    }

    /**
     * Set descriptionProjet
     *
     * @param string $descriptionProjet
     *
     * @return Projets
     */
    public function setDescriptionProjet($descriptionProjet)
    {
        $this->descriptionProjet = $descriptionProjet;

        return $this;
    }

    /**
     * Get descriptionProjet
     *
     * @return string
     */
    public function getDescriptionProjet()
    {
        return $this->descriptionProjet;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Projets
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->media = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dons = new \Doctrine\Common\Collections\ArrayCollection();
        $this->selectionne = false;
    }

    /**
     * Set objectif
     *
     * @param integer $objectif
     *
     * @return Projets
     */
    public function setObjectif($objectif)
    {
        $this->objectif = $objectif;

        return $this;
    }

    /**
     * Get objectif
     *
     * @return integer
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
     * @return Projets
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

    /**
     * Set categorie
     *
     * @param \AdminBundle\Entity\Categories $categorie
     *
     * @return Projets
     */
    public function setCategorie(\AdminBundle\Entity\Categories $categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \AdminBundle\Entity\Categories
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set porteur
     *
     * @param \AdminBundle\Entity\Porteur $porteur
     *
     * @return Projets
     */
    public function setPorteur(\AdminBundle\Entity\Porteur $porteur)
    {
        $this->porteur = $porteur;

        return $this;
    }

    /**
     * Get porteur
     *
     * @return \AdminBundle\Entity\Porteur
     */
    public function getPorteur()
    {
        return $this->porteur;
    }

    /**
     * Add medium
     *
     * @param \AdminBundle\Entity\media $medium
     *
     * @return Projets
     */
    public function addMedia(\AdminBundle\Entity\media $medium)
    {
        $this->media[] = $medium;

        return $this;
    }

    /**
     * Remove medium
     *
     * @param \AdminBundle\Entity\media $medium
     */
    public function removeMedia(\AdminBundle\Entity\media $medium)
    {
        $this->media->removeElement($medium);
    }

    /**
     * Get media
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Add don
     *
     * @param \AdminBundle\Entity\Dons $don
     *
     * @return Projets
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
     * Set selectionne
     *
     * @param boolean $selectionne
     *
     * @return Projets
     */
    public function setSelectionne($selectionne)
    {
        $this->selectionne = $selectionne;

        return $this;
    }

    /**
     * Get selectionne
     *
     * @return boolean
     */
    public function getSelectionne()
    {
        return $this->selectionne;
    }
    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $planFile
     *
     * @return planFile
     */

    public function setPlanFile(File $docPlanAffaire = null)
    {
        $this->planFile = $docPlanAffaire;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        /* if ($img) {
             // if 'updatedAt' is not defined in your entity, use another property
          //   $this->updatedAt = new \DateTime('now');
         }*/
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $ImageFile
     *
     * @return ImageFile
     */

    public function setImageFile(File $logo = null)
    {
        $this->imageFile = $logo;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        /* if ($img) {
             // if 'updatedAt' is not defined in your entity, use another property
          //   $this->updatedAt = new \DateTime('now');
         }*/
    }




    public function getPlanFile()
    {
        return $this->planFile;
    }


    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set descriptionCourteProjet
     *
     * @param string $descriptionCourteProjet
     *
     * @return Projets
     */
    public function setDescriptionCourteProjet($descriptionCourteProjet)
    {
        $this->descriptionCourteProjet = $descriptionCourteProjet;

        return $this;
    }

    /**
     * Get descriptionCourteProjet
     *
     * @return string
     */
    public function getDescriptionCourteProjet()
    {
        return $this->descriptionCourteProjet;
    }

    /**
     * Set dateValidation
     *
     * @param \DateTime $dateValidation
     *
     * @return Projets
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

    /**
     * Set budgetPropose
     *
     * @param integer $budgetPropose
     *
     * @return Projets
     */
    public function setBudgetPropose($budgetPropose)
    {
        $this->budgetPropose = $budgetPropose;

        return $this;
    }

    /**
     * Get budgetPropose
     *
     * @return integer
     */
    public function getBudgetPropose()
    {
        return $this->budgetPropose;
    }

    /**
     * Set docPlanAffaire
     *
     * @param string $docPlanAffaire
     *
     * @return Projets
     */
    public function setDocPlanAffaire($docPlanAffaire)
    {
        $this->docPlanAffaire = $docPlanAffaire;

        return $this;
    }

    /**
     * Get docPlanAffaire
     *
     * @return string
     */
    public function getDocPlanAffaire()
    {
        return $this->docPlanAffaire;
    }

    /**
     * Add remboursement
     *
     * @param \AdminBundle\Entity\Remboursement $remboursement
     *
     * @return Projets
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
