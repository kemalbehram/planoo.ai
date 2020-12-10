<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LegalForm
 *
 * @ORM\Table(name="bp_legal_form")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\LegalFormRepository")
 */
class LegalForm {

    /**
     * Constructor
     */
    public function __construct() {
        $this->informations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=5)
     */
    protected $code;

    /** @ORM\ManyToMany(targetEntity="Country", inversedBy="legalForms", cascade={"persist"})
     *  @ORM\JoinTable(name="legal_form_country",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_legal_form", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_country", referencedColumnName="id")
     *   }
     * )
     */
    private $countries;

    /**
     * @var Information
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Information", mappedBy="legalForm", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $informations;

    /**
     * @var string
     *
     * @ORM\Column(name="canBeIR", type="boolean",  options={"default"=0})
     */
    protected $ir;

    /**
     * @var string
     *
     * @ORM\Column(name="canBeIS", type="boolean",  options={"default"=0})
     */
    protected $is;

    /**
     * @var string
     *
     * @ORM\Column(name="isPersonneMorale", type="boolean", nullable=false,  options={"default"=1})
     */
    protected $personneMorale;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return LegalForm
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return LegalForm
     */
    public function setCode($code) {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Add country
     *
     * @param \BPBundle\Entity\Country $country
     *
     * @return LegalForm
     */
    public function addCountry(\BPBundle\Entity\Country $country) {
        $this->countries[] = $country;

        return $this;
    }

    /**
     * Remove country
     *
     * @param \BPBundle\Entity\Country $country
     */
    public function removeCountry(\BPBundle\Entity\Country $country) {
        $this->countries->removeElement($country);
    }

    /**
     * Get countries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCountries() {
        return $this->countries;
    }

    /**
     * Add information
     *
     * @param \BPBundle\Entity\Information $information
     *
     * @return LegalForm
     */
    public function addInformation(\BPBundle\Entity\Information $information) {
        $this->informations[] = $information;

        return $this;
    }

    /**
     * Remove information
     *
     * @param \BPBundle\Entity\Information $information
     */
    public function removeInformation(\BPBundle\Entity\Information $information) {
        $this->informations->removeElement($information);
    }

    /**
     * Get informations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInformations() {
        return $this->informations;
    }

    public function getIr() {
        return $this->ir;
    }

    public function getIs() {
        return $this->is;
    }

    public function setIr($ir) {
        $this->ir = $ir;
        return $this;
    }

    public function setIs($is) {
        $this->is = $is;
        return $this;
    }

    public function getPersonneMorale() {
        return $this->personneMorale;
    }

    public function setPersonneMorale($personneMorale) {
        $this->personneMorale = $personneMorale;
    }

}
