<?php

namespace PartnersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Partner
 *
 * @ORM\Table(name="partners_partner")
 * @ORM\Entity(repositoryClass="PartnersBundle\Repository\PartnerRepository")
 */
class Partner {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="idWordpressAffiliate", unique=true, type="integer", nullable=true)
     */
    private $idWordpressAffiliate;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=64, unique=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="customDomain", type="string", length=128, unique=true, nullable=true)
     */
    private $customDomain;

    /**
     * @var blob
     *
     * @ORM\Column(name="logo", type="blob", nullable=true)
     */
    private $logo;

    /**
     * @var blob
     *
     * @ORM\Column(name="cobrandingLogo", type="blob", nullable=true)
     */
    private $cobrandingLogo;

    /**
     * @var blob
     *
     * @ORM\Column(name="favicon16", type="blob", nullable=true)
     */
    private $favicon16;

    /**
     * @var blob
     *
     * @ORM\Column(name="favicon32", type="blob", nullable=true)
     */
    private $favicon32;

    /**
     * @var 
     * @ORM\OneToMany(targetEntity="PromotionBundle\Entity\Coupon", mappedBy="partner", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $coupons;

    /**
     * @var 
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\User", mappedBy="partner", cascade={"persist"})
     */
    protected $users;

    /**
     * @ORM\column(name="deletedAt", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Partner
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Set customDomain
     *
     * @param string $customDomain
     *
     * @return Partner
     */
    public function setCustomDomain($customDomain) {
        $this->customDomain = $customDomain;

        return $this;
    }

    /**
     * Get customDomain
     *
     * @return string
     */
    public function getCustomDomain() {
        return $this->customDomain;
    }

    /**
     * Set logo
     *
     * @param blob $logo
     *
     * @return Partner
     */
    public function setLogo($logo) {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return blob
     */
    public function getLogo() {
        return $this->logo;
    }

    /**
     * Set cobrandingLogo
     *
     * @param blob $cobrandingLogo
     *
     * @return Partner
     */
    public function setCobrandingLogo($cobrandingLogo) {
        $this->cobrandingLogo = $cobrandingLogo;

        return $this;
    }

    /**
     * Get cobrandingLogo
     *
     * @return blob
     */
    public function getCobrandingLogo() {
        return $this->cobrandingLogo;
    }

    public function getCoupons() {
        return $this->coupons;
    }

    public function setCoupons($coupons) {
        $this->coupons = $coupons;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return BusinessPlan
     */
    public function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt() {
        return $this->deletedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return BusinessPlan
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return BusinessPlan
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function getUsers() {
        return $this->users;
    }

    public function setUsers($users) {
        $this->users = $users;
    }

    public function getFavicon16() {
        return $this->favicon16;
    }

    public function getFavicon32() {
        return $this->favicon32;
    }

    public function setFavicon16($favicon16) {
        $this->favicon16 = $favicon16;
    }

    public function setFavicon32($favicon32) {
        $this->favicon32 = $favicon32;
    }


    /**
     * Get the value of idWordpressAffiliate
     *
     * @return  int
     */ 
    public function getIdWordpressAffiliate()
    {
        return $this->idWordpressAffiliate;
    }

    /**
     * Set the value of idWordpressAffiliate
     *
     * @param  int  $idWordpressAffiliate
     *
     * @return  self
     */ 
    public function setIdWordpressAffiliate(int $idWordpressAffiliate)
    {
        $this->idWordpressAffiliate = $idWordpressAffiliate;

        return $this;
    }
}
