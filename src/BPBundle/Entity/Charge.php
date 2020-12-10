<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Charge
 *
 * @ORM\Table(name="bp_charge")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\ChargeRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Charge {

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
     * @ORM\Column(name="otherChoice", type="string", length=255, nullable=true)
     */
    private $otherChoice;

    /**
     * @var float
     * @ORM\Column(name="taux", type="float", nullable=true)
     */
    private $taux;

    /**
     * @var float
     *
     * @ORM\Column(name="$tva", type="float", length=255, nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "La TVA doit être comprise entre 0 et 100%",
     *      maxMessage = "La TVA doit être comprise entre 0 et 100%",
     * )
     */
    protected $tva;

    /**
     * @var string
     *
     * @ORM\Column(name="termeEchu", type="boolean",  options={"default"=1})
     */
    protected $termeEchu;

    /**
     * @var integer
     * 
     * @ORM\Column(name="periodicite", type="integer", options={"default"=1})
     * @Assert\Range(
     *      min = 0,
     *      max = 12,
     * )
     */
    protected $periodicite;

    /**
     * Délai de paiement théorique des fournisseurs (en nbre de jours d'achats) spécifique pour la charge
     * @var float
     *
     * @ORM\Column(name="providerDelay", type="integer", nullable=true)
     */
    protected $providerDelay;

    /**
     * @var BusinessPlan
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\BusinessPlan", inversedBy="charges", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $businessPlan;

    /**
     * @var ChargeLabel
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\ChargeLabel", inversedBy="charges", cascade={"persist"})
     */
    protected $chargeLabel;

    /**
     * @var InfoCharge
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\InfoCharge", mappedBy="charge", cascade={"persist", "remove"})
     */
    protected $infoCharges;

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
     * Set otherChoice
     *
     * @param string $otherChoice
     *
     * @return Charge
     */
    public function setOtherChoice($otherChoice) {
        $this->otherChoice = $otherChoice;

        return $this;
    }

    /**
     * Get otherChoice
     *
     * @return string
     */
    public function getOtherChoice() {
        return $this->otherChoice;
    }

    /**
     * Set taux
     *
     * @param float $taux
     *
     * @return Charge
     */
    public function setTaux($taux) {
        $this->taux = $taux;

        return $this;
    }

    /**
     * Get taux
     *
     * @return float
     */
    public function getTaux() {
        return $this->taux;
    }

    /**
     * Set businessPlan
     *
     * @param \BPBundle\Entity\BusinessPlan $businessPlan
     *
     * @return Charge
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

    public function getTva() {
        return $this->tva;
    }

    public function setTva($tva) {
        $this->tva = $tva;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->infoCharges = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add infoCharge
     *
     * @param \BPBundle\Entity\InfoCharge $infoCharge
     *
     * @return Charge
     */
    public function addInfoCharge(\BPBundle\Entity\InfoCharge $infoCharge) {
        $this->infoCharges[] = $infoCharge;

        return $this;
    }

    /**
     * Remove infoCharge
     *
     * @param \BPBundle\Entity\InfoCharge $infoCharge
     */
    public function removeInfoCharge(\BPBundle\Entity\InfoCharge $infoCharge) {
        $this->infoCharges->removeElement($infoCharge);
    }

    /**
     * Get infoCharges
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInfoCharges() {
        /*
         * if a list of exercices is set in the bp
         * the "Produit" has to have one infoProduct for each exercices
         * 
         * If it's not the case, new infoProduct are created empty
         */

        //1 - Check if the number of infoProduct is compliant with the number of exercices
        if (count($this->infoCharges) < count($this->businessPlan->getExercices())) {
            //2 - For each exercice we have to check if it has one infoProduct linked with the actual "Produit"
            foreach ($this->businessPlan->getExercices() as $exercice) {
                $foundInfoCharge = null;
                foreach ($exercice->getInfoCharge() as $exerciceInfoCharge) {
                    if ($exerciceInfoCharge->getCharge() == $this) {
                        $foundInfoCharge = $exerciceInfoCharge;
                    }
                }
                if ($foundInfoCharge == null) {
                    $this->generateOneInfoCharge($exercice);
                }
            }
        }

        return $this->infoCharges;
    }

    public function generateOneInfoCharge($exercice) {
        $infoChargeExercice = new InfoCharge();
        $infoChargeExercice->setCharge($this)->setExercice($exercice);
        $this->addInfoCharge($infoChargeExercice);

        return $this;
    }

    /**
     * Set ChargeLabel
     *
     * @param \BPBundle\Entity\ChargeLabel $chargeLabel
     *
     * @return Charge
     */
    public function setChargeLabel(\BPBundle\Entity\ChargeLabel $chargeLabel = null) {
        $this->chargeLabel = $chargeLabel;

        return $this;
    }

    /**
     * Get ChargeLabel
     *
     * @return \BPBundle\Entity\ChargeLabel
     */
    public function getChargeLabel() {
        return $this->chargeLabel;
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

    public function getProviderDelay() {
        return $this->providerDelay;
    }

    public function setProviderDelay($providerDelay) {
        $this->providerDelay = $providerDelay;
    }

    public function getTermeEchu() {
        return $this->termeEchu;
    }

    public function getPeriodicite() {
        return $this->periodicite;
    }

    public function setTermeEchu($termeEchu) {
        $this->termeEchu = $termeEchu;
    }

    public function setPeriodicite($periodicite) {
        $this->periodicite = $periodicite;
    }

}
