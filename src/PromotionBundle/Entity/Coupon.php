<?php

namespace PromotionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PromotionBundle\Entity\CouponKind;
use PromotionBundle\Entity\CouponRange;

/**
 * Coupon
 *
 * @ORM\Table(name="promotion_coupon")
 * @ORM\Entity(repositoryClass="PromotionBundle\Repository\CouponRepository")
 */
class Coupon {

    /**
     * Coupon constructor.
     */
    public function __construct() {
        $this->startsAt = new \DateTime();
        $this->endsAt = null;
        $this->minimumAmount = null;
        $this->nbUsed = 0;
        $this->nbMaxUsed = 0;
        $this->partner = null;
        $this->range = CouponRange::CART;
        $this->kind = CouponKind::AMOUNT;
        $this->value = 0;
    }

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
     * @ORM\Column(name="code", type="string", unique=true, length=255)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="used", type="integer", nullable=true)
     */
    private $nbUsed;

    /**
     * @var integer
     *
     * @ORM\Column(name="maxUsed", type="integer", nullable=true)
     */
    private $nbMaxUsed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="starts_at", type="datetime", nullable=true)
     */
    private $startsAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ends_at", type="datetime", nullable=true)
     */
    private $endsAt;

    /**
     * @ORM\OneToMany(targetEntity="PaymentBundle\Entity\Cart", mappedBy="coupon")
     */
    private $carts;

    /**
     * @var float
     *
     * @ORM\Column(name="minimumAmount", type="float", nullable=true)
     */
    private $minimumAmount;

    /**
     * Range of the reduction (cart cart, bp only or options only)
     * @var string
     * 
     *
     * @ORM\Column(name="couponRange", type="string", nullable=true)
     */
    private $range;

    /**
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\User", inversedBy="coupons", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="\PartnersBundle\Entity\Partner", inversedBy="coupons")
     *
     */
    private $partner = null;

    /**
     * @var string
     *
     * @ORM\Column(name="kind", type="string", length=255)
     */
    private $kind;

    /**
     * @var float
     *
     * @ORM\Column(name="couponValue", type="float")
     */
    private $value;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="sentDate", type="datetime", nullable=true)
     */
    private $sent = null;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    public function getSent() {
        return $this->sent;
    }

    public function setSent($sent) {
        $this->sent = $sent;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Coupon
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
     * Set name
     *
     * @param string $name
     *
     * @return Coupon
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
     * Set startsAt
     *
     * @param \DateTime $startsAt
     *
     * @return Coupon
     */
    public function setStartsAt($startsAt) {
        $this->startsAt = $startsAt;

        return $this;
    }

    /**
     * Get startsAt
     *
     * @return \DateTime
     */
    public function getStartsAt() {
        return $this->startsAt;
    }

    /**
     * Set endsAt
     *
     * @param \DateTime $endsAt
     *
     * @return Coupon
     */
    public function setEndsAt($endsAt) {
        $this->endsAt = $endsAt;

        return $this;
    }

    /**
     * Get endsAt
     *
     * @return \DateTime
     */
    public function getEndsAt() {
        return $this->endsAt;
    }

    /**
     * Add user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Coupon
     */
    public function addUser(\UserBundle\Entity\User $user) {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \UserBundle\Entity\User $user
     */
    public function removeUser(\UserBundle\Entity\User $user) {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers() {
        return $this->users;
    }

    public function getNbUsed() {
        return $this->nbUsed;
    }

    public function getCarts() {
        return $this->carts;
    }

    public function getMinimumAmount() {
        return $this->minimumAmount;
    }

    public function getRange() {
        return $this->range;
    }

    public function getPartner() {
        return $this->partner;
    }

    public function getKind() {
        return $this->kind;
    }

    public function getValue() {
        return $this->value;
    }

    public function setNbUsed($nbUsed) {
        $this->nbUsed = $nbUsed;
    }

    public function setCarts($carts) {
        $this->carts = $carts;
    }

    public function setMinimumAmount($minimumAmount) {
        $this->minimumAmount = $minimumAmount;
    }

    public function setRange($range) {
        $this->range = $range;
    }

    public function setPartner($partner) {
        $this->partner = $partner;
    }

    public function setKind($type) {
        $this->kind = $type;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getNbMaxUsed() {
        return $this->nbMaxUsed;
    }

    public function setNbMaxUsed($nbMaxUsed) {
        $this->nbMaxUsed = $nbMaxUsed;
    }

}
