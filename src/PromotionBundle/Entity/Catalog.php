<?php

namespace PromotionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Catalog
 *
 * @ORM\Table(name="promotion_catalog")
 * @ORM\Entity(repositoryClass="PromotionBundle\Repository\CatalogRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Catalog {

    const CATALOG_1H_EXPERT_FORMULA_ID = 26;
    const CATALOG_PREZ_PROJECT_FORMULA_ID = 35;
    
    const CATALOG_FORMULE_ESSENTIELLE = 29;
    const CATALOG_FORMULE_PRO = 30;
    const CATALOG_FORMULE_PREMIUM = 31;
    
    const CATALOG_TYPE_BP = 'Business Plan';
    const CATALOG_TYPE_BP_UPGRADE = 'BP Upgrade';
    const CATALOG_TYPE_BP_INTERNAL_SERVICE = 'Service BP Interne';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", unique=false)
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
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255)
     */
    private $icon;

    /**
     * @var string
     *
     * @ORM\Column(name="pickupline", type="string", length=255)
     */
    private $pickupline;

    /**
     * @var boolean
     * @ORM\Column(name="highlight", type="boolean", nullable=false,  options={"default"=0})
     */
    protected $highlight;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;

    /**
     * @var CommandeCatalog
     * @ORM\OneToMany(targetEntity="PromotionBundle\Entity\CommandeCatalog", mappedBy="catalog", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $commandeCatalogs;

    /**
     * @var boolean
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    protected $type;

    /**
     * @var boolean
     * @ORM\Column(name="hasWording", type="boolean", nullable=false,  options={"default"=0})
     */
    protected $hasWording;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_day_expire_edit_date", type="integer",  options={"default"=0})
     */
    private $nbDayForExpireEditDate = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="nb_advice_hour", type="float",  options={"default"=0})
     */
    private $nbAdviceHour = 0;

    /**
     * @var boolean
     * @ORM\Column(name="buyable", type="boolean",  options={"default"=1})
     */
    protected $buyable;

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
     * @ORM\column(name="deletedAt", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @var Catalog
     * @ORM\ManyToOne(targetEntity="Catalog", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $catalogSource;

    /**
     * @var Catalog
     * @ORM\ManyToOne(targetEntity="Catalog", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $catalogDestination;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Catalog
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
     * Set description
     *
     * @param string $description
     *
     * @return Catalog
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Catalog
     */
    public function setPrice($price) {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice() {
        return $this->price;
    }

    public function getNbDayForExpireEditDate() {
        return $this->nbDayForExpireEditDate;
    }

    public function setNbDayForExpireEditDate($nbDayForExpireEditDate) {
        $this->nbDayForExpireEditDate = $nbDayForExpireEditDate;
    }

    public function getNbAdviceHour() {
        return $this->nbAdviceHour;
    }

    public function setNbAdviceHour($nbAdviceHour) {
        $this->nbAdviceHour = $nbAdviceHour;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->bp = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add commandeCatalog
     *
     * @param \PromotionBundle\Entity\CommandeCatalog $commandeCatalog
     *
     * @return Catalog
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

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return Catalog
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


    
    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getHasWording() {
        return $this->hasWording;
    }

    public function setHasWording($hasWording) {
        $this->hasWording = $hasWording;
    }

    public function getBuyable() {
        return $this->buyable;
    }

    public function setBuyable($buyable) {
        $this->buyable = $buyable;
    }

    /**
     * Get the value of catalogSource
     *
     * @return  Catalog
     */ 
    public function getCatalogSource()
    {
        return $this->catalogSource;
    }

    /**
     * Set the value of catalogSource
     *
     * @param  Catalog  $catalogSource
     *
     * @return  self
     */ 
    public function setCatalogSource(Catalog $catalogSource)
    {
        $this->catalogSource = $catalogSource;

        return $this;
    }

    /**
     * Get the value of catalogDestination
     *
     * @return  Catalog
     */ 
    public function getCatalogDestination()
    {
        return $this->catalogDestination;
    }

    /**
     * Set the value of catalogDestination
     *
     * @param  Catalog  $catalogDestination
     *
     * @return  self
     */ 
    public function setCatalogDestination(Catalog $catalogDestination)
    {
        $this->catalogDestination = $catalogDestination;

        return $this;
    }

    /**
     * Get the value of icon
     *
     * @return  string
     */ 
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set the value of icon
     *
     * @param  string  $icon
     *
     * @return  self
     */ 
    public function setIcon(string $icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get the value of pickupline
     *
     * @return  string
     */ 
    public function getPickupline()
    {
        return $this->pickupline;
    }

    /**
     * Set the value of pickupline
     *
     * @param  string  $pickupline
     *
     * @return  self
     */ 
    public function setPickupline(string $pickupline)
    {
        $this->pickupline = $pickupline;

        return $this;
    }

    /**
     * Get the value of highlight
     *
     * @return  boolean
     */ 
    public function getHighlight()
    {
        return $this->highlight;
    }

    /**
     * Set the value of highlight
     *
     * @param  boolean  $highlight
     *
     * @return  self
     */ 
    public function setHighlight($highlight)
    {
        $this->highlight = $highlight;

        return $this;
    }
}
