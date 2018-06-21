<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categories
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\CategoriesRepository")
 */
class Categories
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
     * @ORM\Column(name="nomCategorie", type="string", length=255)
     */
    private $nomCategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionCategorie", type="text", nullable=true)
     */
    private $descriptionCategorie;

    /**
     * @ORM\OneToMany(targetEntity="AdminBundle\Entity\Projets", mappedBy="categorie")
     */
    private $projet;


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
     * Set nomCategorie
     *
     * @param string $nomCategorie
     *
     * @return Categories
     */
    public function setNomCategorie($nomCategorie)
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }

    /**
     * Get nomCategorie
     *
     * @return string
     */
    public function getNomCategorie()
    {
        return $this->nomCategorie;
    }

    /**
     * Set descriptionCategorie
     *
     * @param string $descriptionCategorie
     *
     * @return Categories
     */
    public function setDescriptionCategorie($descriptionCategorie)
    {
        $this->descriptionCategorie = $descriptionCategorie;

        return $this;
    }

    /**
     * Get descriptionCategorie
     *
     * @return string
     */
    public function getDescriptionCategorie()
    {
        return $this->descriptionCategorie;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projet = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add projet
     *
     * @param \AdminBundle\Entity\Projets $projet
     *
     * @return Categories
     */
    public function addProjet(\AdminBundle\Entity\Projets $projet)
    {
        $this->projet[] = $projet;

        return $this;
    }

    /**
     * Remove projet
     *
     * @param \AdminBundle\Entity\Projets $projet
     */
    public function removeProjet(\AdminBundle\Entity\Projets $projet)
    {
        $this->projet->removeElement($projet);
    }

    /**
     * Get projet
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjet()
    {
        return $this->projet;
    }
}
