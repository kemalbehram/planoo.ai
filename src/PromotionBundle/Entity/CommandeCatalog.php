<?php

namespace PromotionBundle\Entity;

use BPBundle\Entity\BusinessPlan;
use PromotionBundle\Entity\Catalog;
use PaymentBundle\Entity\Cart;
use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeCatalog
 *
 * @ORM\Table(name="join_cart_catalog")
 * @ORM\Entity(repositoryClass="PromotionBundle\Repository\CommandeCatalogRepository")
 */
class CommandeCatalog
{
    public function __construct()
    {
        $this->commandeCatalogs = true;
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
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity = 0;

    /**
     * @var Cart
     * @ORM\ManyToOne(targetEntity="PaymentBundle\Entity\Cart", inversedBy="commandeCatalogs", cascade={"persist"})
     */
    protected $cart;


    /**
     * @var Catalog
     * @ORM\ManyToOne(targetEntity="PromotionBundle\Entity\Catalog", inversedBy="commandeCatalogs", cascade={"persist"})
     */
    protected $catalog;

    /**
     * @var BusinessPlan
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\BusinessPlan",  inversedBy="commandeCatalogs", cascade={"persist"})
     */
    protected $businessPlan;

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
     * Set quantity
     *
     * @param boolean $quantity
     *
     * @return CommandeCatalog
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set cart
     *
     * @param Cart $cart
     *
     * @return CommandeCatalog
     */
    public function setCart(Cart $cart = null)
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Get cart
     *
     * @return Cart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * Set catalog
     *
     * @param Catalog $catalog
     *
     * @return CommandeCatalog
     */
    public function setCatalog(Catalog $catalog = null)
    {
        $this->catalog = $catalog;

        return $this;
    }

    /**
     * Get catalog
     *
     * @return Catalog
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

    /**
     * Set businessPlan
     *
     * @param BusinessPlan $businessPlan
     *
     * @return CommandeCatalog
     */
    public function setBusinessPlan(BusinessPlan $businessPlan = null)
    {
        $this->businessPlan = $businessPlan;

        return $this;
    }

    /**
     * Get businessPlan
     *
     * @return BusinessPlan
     */
    public function getBusinessPlan()
    {
        return $this->businessPlan;
    }
}
