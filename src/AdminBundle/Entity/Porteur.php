<?php

namespace AdminBundle\Entity;

use ActeurBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Porteur
 *
 * @ORM\Table(name="porteur")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\PorteurRepository")
 * @Vich\Uploadable
 * @UniqueEntity(fields = "username", targetClass = "ActeurBundle\Entity\User", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "ActeurBundle\Entity\User", message="fos_user.email.already_used")
 */
class Porteur extends User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected  $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * \DateTime $dateNaissance
     *
     * @ORM\Column(name="DateNaissance", type="string", length=255, nullable=true)
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="lieuNaissance", type="string", length=255, nullable=true)
     */
    private $lieuNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255, nullable=true)
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $ville;

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
     * @ORM\Column(name="raison_sociale", type="string", length=255, nullable=true)
     */
    private $raisonSociale;
    /**
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Nature")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nature;

    /**
     * @ORM\OneToMany(targetEntity="AdminBundle\Entity\Projets", mappedBy="porteur")
     */
    private $projet_porteur;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="cni", type="string", length=255, nullable=true)
     */
    private $cni;

    /**
     * @Vich\UploadableField(mapping="admin_images", fileNameProperty="photo")
     * @var File
     */
    private $imageFile;

    /**
     * @Vich\UploadableField(mapping="admin_images", fileNameProperty="cni")
     * @var File
     */
    private $imageFileCni;

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
     * @return Porteur
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
     * @return Porteur
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
     * @return Porteur
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
     * @return Porteur
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
     * Set raisonSociale
     *
     * @param string $raisonSociale
     *
     * @return Porteur
     */
    public function setRaisonSociale($raisonSociale)
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }


    public function getImageFileCni()
    {
        return $this->imageFileCni;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $ImageFileCni
     * @return ImageFileCni
     */
    public function setImageFileCni(file $img2=null)
    {
        $this->imageFileCni = $img2;
    }

    /**
     * @param array $roles
     */
    public function setRole($role)
    {
        $this->roles = [$role];
    }

    /**
     * Get raisonSociale
     *
     * @return string
     */
    public function getRaisonSociale()
    {
        return $this->raisonSociale;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projet_porteur = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set type
     *
     * @param \AdminBundle\Entity\Nature $type
     *
     * @return Porteur
     */
    public function setNature(\AdminBundle\Entity\Nature $nature)
    {
        $this->nature = $nature;

        return $this;
    }

    /**
     * Get type
     *
     * @return \AdminBundle\Entity\Nature
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     * Add projetPorteur
     *
     * @param \AdminBundle\Entity\Projets $projetPorteur
     *
     * @return Porteur
     */
    public function addProjetPorteur(\AdminBundle\Entity\Projets $projetPorteur)
    {
        $this->projet_porteur[] = $projetPorteur;

        return $this;
    }

    /**
     * Remove projetPorteur
     *
     * @param \AdminBundle\Entity\Projets $projetPorteur
     */
    public function removeProjetPorteur(\AdminBundle\Entity\Projets $projetPorteur)
    {
        $this->projet_porteur->removeElement($projetPorteur);
    }

    /**
     * Get projetPorteur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjetPorteur()
    {
        return $this->projet_porteur;
    }
    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $ImageFile
     *
     * @return ImageFile
     */

    public function setImageFile(File $img = null)
    {
        $this->imageFile = $img;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
       /* if ($img) {
            // if 'updatedAt' is not defined in your entity, use another property
         //   $this->updatedAt = new \DateTime('now');
        }*/
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }


    /**
     * Set img
     *
     * @param string $img
     *
     * @return Porteur
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->photo;
    }

    /**
     * Set dateNaissance
     *
     * @param string $dateNaissance
     *
     * @return Porteur
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return string
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set lieuNaissance
     *
     * @param string $lieuNaissance
     *
     * @return Porteur
     */
    public function setLieuNaissance($lieuNaissance)
    {
        $this->lieuNaissance = $lieuNaissance;

        return $this;
    }

    /**
     * Get lieuNaissance
     *
     * @return string
     */
    public function getLieuNaissance()
    {
        return $this->lieuNaissance;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Porteur
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Porteur
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Porteur
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set cni
     *
     * @param string $cni
     *
     * @return Porteur
     */
    public function setCni($cni)
    {
        $this->cni = $cni;

        return $this;
    }

    /**
     * Get cni
     *
     * @return string
     */
    public function getCni()
    {
        return $this->cni;
    }
}
