<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use UserBundle\Entity\SocialCharge;

/**
 * Country
 *
 * @ORM\Table(name="bp_country")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\CountryRepository")
 */
class Country
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addresses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->amortissements = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @ORM\Column(name="code", type="string", length=3)
     */
    protected $code;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;


    /**
     * @var string
     *
     * @ORM\Column(name="flag", type="string", length=255, nullable=true)
     */
    protected $flag;


    /**
     *
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"image/jpg", "image/jpeg","image/gif","image/png" },
     *     mimeTypesMessage = "Extensions autorisées jpeg,jpg,gif,png",
     *     maxSizeMessage = "Merci d'uploader un fichier inférieur ou à égal à 1000k"
     * )
     */
    protected $file;

    /**
     * @var Address
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Address", mappedBy="country", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $addresses;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="LegalForm", mappedBy="countries")
     */
    protected $legalForms;


    /**
     * @var Rate
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Rate", mappedBy="country", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $rates;


    /**
     * @var SocialCharge
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\SocialCharge", mappedBy="country", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $socialCharges;

    /**
     * @var Amortissement
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Amortissement", mappedBy="country")
     */
    protected $amortissements;

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }


    public function upload()
    {
        if(null === $this->file)
        {
            return;
        }
        $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());
        $this->flag=$this->file->getClientOriginalName();

    }

    public function getUploadDir()
    {
        return "uploads/country";
    }


    public function getWebPath()
    {
        if(null === $this->flag)
        {
            return null;
        }

        return $this->getUploadDir().'/'.$this->flag;
    }

    public function getUploadRootDir()
    {
        return __DIR__.'/../../../web/'.$this->getUploadDir();

    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Country
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Country
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
     * Set flag
     *
     * @param string $flag
     *
     * @return Country
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     *
     * @return string
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Add address
     *
     * @param \BPBundle\Entity\Address $address
     *
     * @return Country
     */
    public function addAddress(\BPBundle\Entity\Address $address)
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Remove address
     *
     * @param \BPBundle\Entity\Address $address
     */
    public function removeAddress(\BPBundle\Entity\Address $address)
    {
        $this->addresses->removeElement($address);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Add legalForm
     *
     * @param \BPBundle\Entity\LegalForm $legalForm
     *
     * @return Country
     */
    public function addLegalForm(\BPBundle\Entity\LegalForm $legalForm)
    {
        $this->legalForms[] = $legalForm;

        return $this;
    }

    /**
     * Remove legalForm
     *
     * @param \BPBundle\Entity\LegalForm $legalForm
     */
    public function removeLegalForm(\BPBundle\Entity\LegalForm $legalForm)
    {
        $this->legalForms->removeElement($legalForm);
    }

    /**
     * Get legalForms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLegalForms()
    {
        return $this->legalForms;
    }

    /**
     * Add rate
     *
     * @param \BPBundle\Entity\Rate $rate
     *
     * @return Country
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

    /**
     * Add socialCharge
     *
     * @param \UserBundle\Entity\SocialCharge $socialCharge
     *
     * @return Country
     */
    public function addSocialCharge(\UserBundle\Entity\SocialCharge $socialCharge)
    {
        $this->socialCharges[] = $socialCharge;

        return $this;
    }

    /**
     * Remove socialCharge
     *
     * @param \UserBundle\Entity\SocialCharge $socialCharge
     */
    public function removeSocialCharge(\UserBundle\Entity\SocialCharge $socialCharge)
    {
        $this->socialCharges->removeElement($socialCharge);
    }

    /**
     * Get socialCharges
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSocialCharges()
    {
        return $this->socialCharges;
    }

    /**
     * Add amortissement
     *
     * @param \BPBundle\Entity\Amortissement $amortissement
     *
     * @return Country
     */
    public function addAmortissement(\BPBundle\Entity\Amortissement $amortissement)
    {
        $this->amortissements[] = $amortissement;

        return $this;
    }

    /**
     * Remove amortissement
     *
     * @param \BPBundle\Entity\Amortissement $amortissement
     */
    public function removeAmortissement(\BPBundle\Entity\Amortissement $amortissement)
    {
        $this->amortissements->removeElement($amortissement);
    }

    /**
     * Get amortissements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAmortissements()
    {
        return $this->amortissements;
    }
}
