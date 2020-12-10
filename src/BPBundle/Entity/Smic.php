<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Smic
 *
 * @ORM\Table(name="bp_smic")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\SmicRepository")
 */
class Smic
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
     * @var float
     *
     * @ORM\Column(name="montant", type="float")
     */
    private $montant;

    /**
     * @var float
     *
     * @ORM\Column(name="coef", type="float")
     */
    private $coef;

    /**
     * @var float
     *
     * @ORM\Column(name="cice", type="float")
     */
    private $cice;


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
     * @param float $montant
     *
     * @return Smic
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return float
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set coef
     *
     * @param float $coef
     *
     * @return Smic
     */
    public function setCoef($coef)
    {
        $this->coef = $coef;

        return $this;
    }

    /**
     * Get coef
     *
     * @return float
     */
    public function getCoef()
    {
        return $this->coef;
    }

    /**
     * @return float
     */
    public function getCice()
    {
        return $this->cice;
    }

    /**
     * @param float $cice
     */
    public function setCice($cice)
    {
        $this->cice = $cice;
    }


}
