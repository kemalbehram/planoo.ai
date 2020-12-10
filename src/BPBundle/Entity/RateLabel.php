<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RateLabel
 *
 * @ORM\Table(name="bp_rate_label")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\RateLabelRepository")
 */
class RateLabel
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var Rate
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Rate", mappedBy="type", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $rates;


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
     * Set name
     *
     * @param string $name
     *
     * @return RateLabel
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rates = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add rate
     *
     * @param \BPBundle\Entity\Rate $rate
     *
     * @return RateLabel
     */
    public function addRate(\BPBundle\Entity\Rate $rate)
    {
        $this->rates[] = $rate;

        return $this;
    }

    /**
     * Remove rate
     *
     * @param \BPBundle\Entity\Rate $rate
     */
    public function removeRate(\BPBundle\Entity\Rate $rate)
    {
        $this->rates->removeElement($rate);
    }

    /**
     * Get rates
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRates()
    {
        return $this->rates;
    }
}
