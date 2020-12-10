<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Amortissement
 *
 * @ORM\Table(name="bp_amortissement")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\AmortissementRepository")
 */
class Amortissement
{

    /**
     * @var ChargeLabel
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\ChargeLabel", inversedBy="amortissements")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_charge_label", referencedColumnName="id")
     * })
     */
    protected $chargeLabel;

    /**
     * @var Country
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\Country", inversedBy="amortissements")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_country", referencedColumnName="id")
     * })
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(name="duree", type="float", nullable=true)
     */
    protected $duree;

    /**
     * Set duree
     *
     * @param float $duree
     *
     * @return Amortissement
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return float
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set chargeLabel
     *
     * @param \BPBundle\Entity\ChargeLabel $chargeLabel
     *
     * @return Amortissement
     */
    public function setChargeLabel(\BPBundle\Entity\ChargeLabel $chargeLabel)
    {
        $this->chargeLabel = $chargeLabel;

        return $this;
    }

    /**
     * Get chargeLabel
     *
     * @return \BPBundle\Entity\ChargeLabel
     */
    public function getChargeLabel()
    {
        return $this->chargeLabel;
    }

    /**
     * Set country
     *
     * @param \BPBundle\Entity\Country $country
     *
     * @return Amortissement
     */
    public function setCountry(\BPBundle\Entity\Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \BPBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }
}
