<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Produit
 *
 * @ORM\Table(name="bp_produit")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\ProduitRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Produit {

    /**
     * Constructor
     */
    public function __construct(BusinessPlan $bp) {
        $this->businessPlan = $bp;
        $this->productSeasons = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @var ProductSeasons
     * @ORM\OneToMany(targetEntity="ProductSeason", mappedBy="produit", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     */
    protected $productSeasons;

    /**
     * @var ProductStockSeasons
     * @ORM\OneToMany(targetEntity="ProductStockSeason", mappedBy="produit", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     */
    protected $productStockSeasons;

    /**
     * @var float
     *
     * @ORM\Column(name="tvaAchats", type="float", length=255, nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "La TVA doit être comprise entre 0 et 100%",
     *      maxMessage = "La TVA doit être comprise entre 0 et 100%",
     * )
     */
    protected $tvaAchats;

    /**
     * @var float
     *
     * @ORM\Column(name="$tvaVentes", type="float", length=255, nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "La TVA doit être comprise entre 0 et 100%",
     *      maxMessage = "La TVA doit être comprise entre 0 et 100%",
     * )
     */
    protected $tvaVentes;

    /**
     * Délai de paiement théorique des clients spécifique (en jours)
     * @var float
     * 
     * @ORM\Column(name="customerDelay", type="float", nullable=true)
     */
    protected $customerDelay;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     * @Assert\NotBlank(message="attention",groups={"income"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="nameCommission", type="string", length=100, nullable=true)
     */
    private $nameCommission;

    /**
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\InfoProduct", mappedBy="produit", cascade={"persist", "remove"})
     */
    protected $infoProduct;

    /**
     * @var BusinessPlan
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\BusinessPlan", inversedBy="produits", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE"))
     */
    protected $businessPlan;

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
     * @return Produit
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

    public function getTvaAchats() {
        return $this->tvaAchats;
    }

    public function getTvaVentes() {
        return $this->tvaVentes;
    }

    public function setTvaAchats($tvaAchats) {
        $this->tvaAchats = $tvaAchats;
    }

    public function setTvaVentes($tvaVentes) {
        $this->tvaVentes = $tvaVentes;
    }

    public function getCustomerDelay() {
        return $this->customerDelay;
    }

    public function setCustomerDelay($customerDelay) {
        $this->customerDelay = $customerDelay;
    }

    /**
     * Set nameCommission
     *
     * @param string $nameCommission
     *
     * @return Produit
     */
    public function setNameCommission($nameCommission) {
        $this->nameCommission = $nameCommission;

        return $this;
    }

    /**
     * Get nameCommission
     *
     * @return string
     */
    public function getNameCommission() {
        return $this->nameCommission;
    }

    /**
     * Set businessPlan
     *
     * @param \BPBundle\Entity\BusinessPlan $businessPlan
     *
     * @return Produit
     */
    public function setBusinessPlan(\BPBundle\Entity\BusinessPlan $businessPlan = null) {
        $this->businessPlan = $businessPlan;

        return $this;
    }

    /**
     * Get businessPlan
     *
     * @return \BPBundle\Entity\BusinessPlan
     */
    public function getBusinessPlan() {
        return $this->businessPlan;
    }

    /**
     * Add infoProduct
     *
     * @param \BPBundle\Entity\InfoProduct $infoProduct
     *
     * @return Produit
     */
    public function addInfoProduct(\BPBundle\Entity\InfoProduct $infoProduct) {
        $this->infoProduct[] = $infoProduct;

        return $this;
    }

    /**
     * Remove infoProduct
     *
     * @param \BPBundle\Entity\InfoProduct $infoProduct
     */
    public function removeInfoProduct(\BPBundle\Entity\InfoProduct $infoProduct) {
        $this->infoProduct->removeElement($infoProduct);
    }

    /**
     * Get infoProduct
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInfoProduct() {
        /*
         * if a list of exercices is set in the bp
         * the "Produit" has to have one infoProduct for each exercices
         * 
         * If it's not the case, new infoProduct are created empty
         */

        //1 - Check if the number of infoProduct is compliant with the number of exercices
        if (count($this->infoProduct) < count($this->businessPlan->getExercices())) {
            //2 - For each exercice we have to check if it has one infoProduct linked with the actual "Produit"
            foreach ($this->businessPlan->getExercices() as $exercice) {
                $foundInfoProduct = null;
                foreach ($exercice->getInfoProduct() as $exerciceInfoProduct) {
                    if ($exerciceInfoProduct->getProduit() == $this) {
                        $foundInfoProduct = $exerciceInfoProduct;
                    }
                }
                if ($foundInfoProduct == null) {
                    $this->generateOneInfoProduct($exercice);
                }
            }
        }

        return $this->infoProduct;
    }

    public function generateOneInfoProduct($exercice) {
        $produitExercice = new InfoProduct();
        $produitExercice->setProduit($this)->setExercice($exercice);
        $this->addInfoProduct($produitExercice);

        return $this;
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

    public function getProductSeasons() {
        return $this->productSeasons;
    }

    public function hasCustomProductSeasons() {
        return $this->productSeasons != null && !$this->productSeasons->isEmpty();
    }

    public function initProductSeason() {
        if ($this->productSeasons) {
            $this->productSeasons->clear();
        } else {
            $this->productSeasons = new \Doctrine\Common\Collections\ArrayCollection();
        }


        foreach ($this->getBusinessPlan()->getSaisonnalites() as $saisonnalite) {
            $this->productSeasons->add(new ProductSeason($this, $saisonnalite->getNomMois(), $saisonnalite->getSaisonCA()));
        }
    }

    public function removeCustomProductSeasons() {
        $this->productSeasons->clear();
    }

    public function sommeProductSeasonSaisonCAExercice($exercice) {

        $sommeCA = 0;

        $date = clone $exercice->getDateDebut();

        $seasonsToProcess = null;

        if (!$this->productSeasons->isEmpty()) {
            $seasonsToProcess = $this->productSeasons;
        } else {
            $seasonsToProcess = $this->getBusinessPlan()->getSaisonnalites();
        }

        while ($date < $exercice->getDateFin()) {
            foreach ($seasonsToProcess as $seasson) {
                if ($seasson->getNomMois() == Saisonnalite::$nomsMois[$date->format('n')]) {
                    $sommeCA += $seasson->getSaisonCA();
                }
            }
            $date->add(new \DateInterval('P1M'));
        }

        return $sommeCA;
    }

    public function getProductSeasonByDate($date) {
        $tabMois = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];

        if ($this->productSeasons != null && !$this->productSeasons->isEmpty()) {
            $seasonsToProcess = $this->productSeasons;
        } else {
            $seasonsToProcess = $this->getBusinessPlan()->getSaisonnalites();
        }

        foreach ($seasonsToProcess as $element) {
            if (array_search($element->getNomMois(), $tabMois) == $date->format('n')) {
                return $element;
            }
        }
    }

    public function getProductStockSeasons() {
        return $this->productStockSeasons;
    }

    public function hasCustomProductStockSeasons() {
        return $this->productStockSeasons != null && !$this->productStockSeasons->isEmpty();
    }

    public function initProductStockSeason() {
        if ($this->productStockSeasons) {
            $this->productStockSeasons->clear();
        } else {
            $this->productStockSeasons = new \Doctrine\Common\Collections\ArrayCollection();
        }

        $nbStockDays = 0;
        if ($this->getBusinessPlan()->getInfoBfr() && $this->getBusinessPlan()->getInfoBfr()->getStock()) {
            $nbStockDays = $this->getBusinessPlan()->getInfoBfr()->getStock();
        }
        while ($this->productStockSeasons->count() < 12) {
            $this->productStockSeasons->add(new ProductStockSeason($this, $nbStockDays));
        }
    }

    public function removeCustomProductStockSeasons() {
        $this->productStockSeasons->clear();
    }

    public function getProductStockSeasonByDate($date) {
        $tabMois = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];

        if ($this->productStockSeasons != null && !$this->productStockSeasons->isEmpty()) {
            $seasonsToProcess = $this->productStockSeasons;
        } else {
            $seasonsToProcess = $this->getBusinessPlan()->getSaisonnalites();
        }

        foreach ($seasonsToProcess as $element) {
            if (array_search($element->getNomMois(), $tabMois) == $date->format('n')) {
                return $element;
            }
        }
    }

}
