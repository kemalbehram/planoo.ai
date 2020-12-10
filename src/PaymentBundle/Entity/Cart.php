<?php

namespace PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use PromotionBundle\Entity\Catalog;
use PromotionBundle\Entity\CouponKind;
use PromotionBundle\Entity\CouponRange;

/**
 * Cart
 *
 * @ORM\Table(name="bp_cart")
 * @ORM\Entity(repositoryClass="PaymentBundle\Repository\CartRepository")
 */
class Cart {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="carts")
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    protected $user;

    /**
     * @var CommandeCatalog
     * @ORM\OneToMany(targetEntity="PromotionBundle\Entity\CommandeCatalog", mappedBy="cart", cascade={"persist", "remove"})
     */
    protected $commandeCatalogs;

    /**
     * @ORM\ManyToOne(targetEntity="PromotionBundle\Entity\Coupon", inversedBy="carts", cascade={"persist"})
     */
    protected $coupon;

    /**
     * @var Payment
     * @ORM\OneToOne(targetEntity="PaymentBundle\Entity\Payment", mappedBy="cart", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    protected $payment;

    private $tauxTva = 0.2;

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
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set paid
     *
     * @param Payment $payment
     *
     * @return Cart
     */
    public function setPayment($payment) {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get paid
     *
     * @return Payment
     */
    public function getPayment() {
        return $this->payment;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Cart
     */
    public function setUser(\UserBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Add commandeCatalog
     *
     * @param \PromotionBundle\Entity\CommandeCatalog $commandeCatalog
     *
     * @return BusinessPlan
     */
    public function addCommandeCatalog(\PromotionBundle\Entity\CommandeCatalog $commandeCatalog) {
        $this->commandeCatalogs[] = $commandeCatalog;

        return $this;
    }

    /**
     * Remove commandeCatalog
     *
     * @param \PromotionBundle\Entity\CommandeCatalog $commandeCatalog
     */
    public function removeCommandeCatalog(\PromotionBundle\Entity\CommandeCatalog $commandeCatalog) {
        $this->commandeCatalogs->removeElement($commandeCatalog);
    }

    /**
     * Get commandeCatalogs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandeCatalogs() {
        return $this->commandeCatalogs;
    }

    public function checkCartIntegrity() {
        if ($this->getCommandeCatalogs()) {
            foreach ($this->getCommandeCatalogs() as $commandeCatalog) {
                try {
                    $commandeCatalog->getCatalog()->getName();
                } catch (\Doctrine\ORM\EntityNotFoundException $e) {
                    $this->removeCommandeCatalog($commandeCatalog);
                }
            }
        }
    }
    /**
     * Get total cart amount HT
     *
     * @return flaot
     */
    public function getTotalAmountHT() {
        return $this->getTotalAmountTTC() / (1 + $this->tauxTva);
    }

    /**
     * Get total cart amount TTC
     *
     * @return float
     */
    public function getTotalAmountTTC() {
        $total = 0;
        if ($this->commandeCatalogs != null) {
            foreach ($this->commandeCatalogs as $commandeCatalog) {
                $total += $commandeCatalog->getCatalog()->getPrice() * $commandeCatalog->getQuantity();
            }
        }

        return $total - $this->getReductionAmountTTC();
    }

    /**
     * Get reduction amount
     *
     * @return float
     */
    public function getReductionAmountTTC() {
        $reduction = 0;
        $amountBP = 0;
        $amountOptions = 0;

        if ($this->commandeCatalogs != null) {
            foreach ($this->commandeCatalogs as $commandeCatalog) {
                if ($commandeCatalog->getCatalog()->getType() === Catalog::CATALOG_TYPE_BP) {
                    $amountBP = $commandeCatalog->getCatalog()->getPrice();
                } else {
                    $amountOptions = $commandeCatalog->getCatalog()->getPrice() * $commandeCatalog->getQuantity();
                }
            }
        }

        $amountTotal = $amountBP + $amountOptions;

        if ($this->coupon != null) {
            switch ($this->coupon->getRange()) {
            case CouponRange::BP_ONLY:
                $assiette = $amountBP;
                break;
            case CouponRange::CART:
                $assiette = $amountTotal;
                break;
            case CouponRange::OPTIONS_ONLY:
                $assiette = $amountOptions;
                break;
            }

            switch ($this->coupon->getKind()) {
            case CouponKind::AMOUNT:
                if ($assiette - $this->coupon->getValue() > 0) {
                    $reduction = $this->coupon->getValue();
                } else {
                    $reduction = $assiette;
                }
                break;
            case CouponKind::PERCENT:
                $reduction = $assiette * $this->coupon->getValue() / 100;
                break;
            }
        }

        return $reduction;
    }

    /**
     * Get tva amount
     *
     * @return float
     */
    public function getTVA() {
        return $this->getTotalAmountTTC() - $this->getTotalAmountHT();
    }

    public function getCoupon() {
        return $this->coupon;
    }

    public function setCoupon($coupon) {
        $this->coupon = $coupon;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Cart
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
     * @return Cart
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

}
