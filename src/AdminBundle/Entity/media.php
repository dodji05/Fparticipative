<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * media
 *
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\mediaRepository")
 * @Vich\Uploadable
 */
class media
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
     * @ORM\Column(name="chemin", type="string", length=255, nullable=true)
     */
    private $chemin;

    /**
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Projets",inversedBy="media",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $projets_media;

    /**
     * @Vich\UploadableField(mapping="admin_images", fileNameProperty="chemin")
     * @var File
     */
    private $imageFile;


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
     * Set chemin
     *
     * @param string $chemin
     *
     * @return media
     */
    public function setChemin($chemin)
    {
        $this->chemin = $chemin;

        return $this;
    }

    /**
     * Get chemin
     *
     * @return string
     */
    public function getChemin()
    {
        return $this->chemin;
    }

    /**
     * Set projetsMedia
     *
     * @param \AdminBundle\Entity\Projets $projetsMedia
     *
     * @return media
     */
    public function setProjetsMedia(\AdminBundle\Entity\Projets $projetsMedia)
    {
        $this->projets_media = $projetsMedia;

        return $this;
    }

    /**
     * Get projetsMedia
     *
     * @return \AdminBundle\Entity\Projets
     */
    public function getProjetsMedia()
    {
        return $this->projets_media;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile ImageFile
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

    public function getImageFile()
    {
        return $this->imageFile;
    }
}
