<?php

namespace UserBundle\Entity;

use BPBundle\Entity\Bfr;
use BPBundle\Entity\BusinessPlan;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;
use PromotionBundle\Entity\Catalog;

/**
 * User
 *
 * @ORM\Table(name="user_user")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\UserRepository")
 *
 */
class User extends BaseUser {

    public function __construct() {
        parent::__construct();
        // your own logic
        $this->addresses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->informations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bfrs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @var Address
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Address", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $addresses;

    /**
     * @var BusinessPlan
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\BusinessPlan", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $businessPlans;

    /**
     * @var Information
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Information", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $informations;

    /**
     * @var Bfr
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Bfr", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $bfrs;

    /**
     * @var Cart
     * @ORM\OneToMany(targetEntity="PaymentBundle\Entity\Cart", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $carts;

    /**
     * @var Cart
     * @ORM\OneToMany(targetEntity="BackBundle\Entity\JoorneyOrder", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $orders;

    /**
     * @var Service
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Service", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $services;

    /**
     * @var string
     *
     * @ORM\Column(name="stripeId", type="string", length=255, nullable=true)
     */
    protected $stripeId;

    /**
     * @ORM\ManyToMany(targetEntity="PromotionBundle\Entity\Coupon", mappedBy="users", cascade={"persist"})
     * inverseJoinColumns={@ORM\JoinColumn(nullable=true)})
     */
    private $coupons;

    /**
     * @ORM\ManyToOne(targetEntity="\PartnersBundle\Entity\Partner", inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     */
    private $partner = null;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $privacyPolicyAcceptedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $newsletterAcceptedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $commercialAcceptedAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phoneNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $lastname;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    public function getId() {
        return $this->id;
    }

    public function getPartner() {
        return $this->partner;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setPartner($partner) {
        $this->partner = $partner;
    }

    /**
     * Add address
     *
     * @param \BPBundle\Entity\Address $address
     *
     * @return User
     */
    public function addAddress(\BPBundle\Entity\Address $address) {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Remove address
     *
     * @param \BPBundle\Entity\Address $address
     */
    public function removeAddress(\BPBundle\Entity\Address $address) {
        $this->addresses->removeElement($address);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses() {
        return $this->addresses;
    }

    /**
     * Add businessPlan
     *
     * @param \BPBundle\Entity\BusinessPlan $businessPlan
     *
     * @return User
     */
    public function addBusinessPlan(\BPBundle\Entity\BusinessPlan $businessPlan) {
        $this->businessPlans[] = $businessPlan;

        return $this;
    }

    /**
     * Remove businessPlan
     *
     * @param \BPBundle\Entity\BusinessPlan $businessPlan
     */
    public function removeBusinessPlan(\BPBundle\Entity\BusinessPlan $businessPlan) {
        $this->businessPlans->removeElement($businessPlan);
    }

    /**
     * Get businessPlans
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBusinessPlans() {
        return $this->businessPlans;
    }

    /**
     * Add information
     *
     * @param \BPBundle\Entity\Information $information
     *
     * @return User
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

    /**
     * Add bfr
     *
     * @param \BPBundle\Entity\Bfr $bfr
     *
     * @return User
     */
    public function addBfr(\BPBundle\Entity\Bfr $bfr) {
        $this->bfrs[] = $bfr;

        return $this;
    }

    /**
     * Remove bfr
     *
     * @param \BPBundle\Entity\Bfr $bfr
     */
    public function removeBfr(\BPBundle\Entity\Bfr $bfr) {
        $this->bfrs->removeElement($bfr);
    }

    /**
     * Get bfrs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBfrs() {
        return $this->bfrs;
    }

    /**
     * Add cart
     *
     * @param \PaymentBundle\Entity\Cart $cart
     *
     * @return User
     */
    public function addCart(\PaymentBundle\Entity\Cart $cart) {
        $this->carts[] = $cart;

        return $this;
    }

    /**
     * Remove cart
     *
     * @param \PaymentBundle\Entity\Cart $cart
     */
    public function removeCart(\PaymentBundle\Entity\Cart $cart) {
        $this->carts->removeElement($cart);
    }

    /**
     * Get carts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarts() {
        return $this->carts;
    }

    /**
     * Set stripeId
     *
     * @param string $stripeId
     *
     * @return User
     */
    public function setStripeId($stripeId) {
        $this->stripeId = $stripeId;

        return $this;
    }

    /**
     * Get stripeId
     *
     * @return string
     */
    public function getStripeId() {
        return $this->stripeId;
    }

    /**
     * Add coupon
     *
     * @param \PromotionBundle\Entity\Coupon $coupon
     *
     * @return User
     */
    public function addCoupon(\PromotionBundle\Entity\Coupon $coupon) {
        $this->coupons[] = $coupon;

        return $this;
    }

    /**
     * Remove coupon
     *
     * @param \PromotionBundle\Entity\Coupon $coupon
     */
    public function removeCoupon(\PromotionBundle\Entity\Coupon $coupon) {
        $this->coupons->removeElement($coupon);
    }

    /**
     * Get coupons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCoupons() {
        return $this->coupons;
    }

    public function setEmail($email) {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);

        return $this;
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

    public function getPrivacyPolicyAcceptedAt() {
        return $this->privacyPolicyAcceptedAt;
    }

    public function getNewsletterAcceptedAt() {
        return $this->newsletterAcceptedAt;
    }

    public function getCommercialAcceptedAt() {
        return $this->commercialAcceptedAt;
    }

    public function setPrivacyPolicyAcceptedAt($privacyPolicyAcceptedAt) {
        $this->privacyPolicyAcceptedAt = $privacyPolicyAcceptedAt;
    }

    public function setNewsletterAcceptedAt($newsletterAcceptedAt) {
        $this->newsletterAcceptedAt = $newsletterAcceptedAt;
    }

    public function setCommercialAcceptedAt($commercialAcceptedAt) {
        $this->commercialAcceptedAt = $commercialAcceptedAt;
    }

    public function getServices() {
        return $this->services;
    }

    public function getOrders() {
        return $this->orders;
    }

    public function setOrders($orders) {
        $this->orders = $orders;
    }

    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Get the value of firstname
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */
    public function setFirstname($firstname) {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of lastname
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;

        return $this;
    }
}
