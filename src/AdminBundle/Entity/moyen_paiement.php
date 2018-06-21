<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * moyen_paiement
 *
 * @ORM\Table(name="moyen_paiement")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\moyen_paiementRepository")
 */
class moyen_paiement
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
     * @ORM\Column(name="moyen", type="string", length=255, nullable=true)
     */
    private $moyen;


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
     * Set moyen
     *
     * @param string $moyen
     *
     * @return moyen_paiement
     */
    public function setMoyen($moyen)
    {
        $this->moyen = $moyen;

        return $this;
    }

    /**
     * Get moyen
     *
     * @return string
     */
    public function getMoyen()
    {
        return $this->moyen;
    }
}
