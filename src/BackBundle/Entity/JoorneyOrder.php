<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PlanooOrder
 *
 * An order is created when a service is activated 
 * 
 * @ORM\Table(name="joorney_order")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\JoorneyOrderRepository")
 */
class JoorneyOrder {

    const ORDER_STATUT_1_PAYMENT_AWAITED = 'Paiement en attente';
    // const ORDER_STATUT_2_PAYMENT_AUTHORIZATION_ACCEPTED = 'Période d\'essai'; // TODO : Useless i think
    const ORDER_STATUT_3_PAYMENT_CANCELED = 'Annulée';
    const ORDER_STATUT_4_ORDER_VALIDATED = 'Commande validé';
    const ORDER_STATUT_5_PREPARATION = 'Préparation en cours';
    const ORDER_STATUT_6_TERMINATED = 'Terminée';
    const ORDER_STATUT_7_REFUNDED = 'Remboursée';

    public function __construct() {
        $this->setHash(uniqid('sv'));
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
     * @ORM\Column(name="hash", type="string", nullable=true)
     */
    protected $hash;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;

    /**
     * @var Payment
     * @ORM\OneToOne(targetEntity="PaymentBundle\Entity\Payment", cascade={"persist"})
     */
    protected $payment;

    /**
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\BusinessPlan", inversedBy="orders", cascade={"persist"})
     */
    protected $businessPlan;

    /**
     * @var Catalog
     * @ORM\ManyToOne(targetEntity="PromotionBundle\Entity\Catalog", inversedBy="commandeCatalogs", cascade={"persist"})
     */
    protected $catalog;

    /**
     * @ORM\ManyToOne(targetEntity="PaymentBundle\Entity\Cart",cascade={"persist"})
     */
    private $cart;

    /**
     * @ORM\Column(name="preferedTime", type="string", nullable=true)
     */
    protected $preferedTime;

    /**
     * @ORM\Column(name="activityField", type="string", nullable=true)
     */
    protected $activityField;

    /**
     * @ORM\Column(name="activityDescription", type="string", nullable=true)
     */
    protected $activityDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="funding", type="string", length=255, nullable=true)
     */
    private $funding;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    protected $filename;

    /**
     * @Assert\file(
     *     maxSize = "20M",
     *     mimeTypes = {"application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/vnd.ms-powerpoint","application/msword","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/pdf","application/vnd.oasis.opendocument.presentation","application/vnd.oasis.opendocument.text" },
     *     mimeTypesMessage = "Extensions autorisées pptx,docx,ppt,doc,pdf,odp, odt",
     *     maxSizeMessage = "Merci d'uploader un fichier inférieur ou à égal à 20Mo",
     * )
     */
    protected $file;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return PlanooOrder
     */
    public function setStatut($statut) {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut() {
        return $this->statut;
    }

    /**
     * Is statut open
     *
     * @return boolean
     */
    public function isOpen() {
        return in_array($this->statut, [
            JoorneyOrder::ORDER_STATUT_1_PAYMENT_AWAITED,
            // JoorneyOrder::ORDER_STATUT_2_PAYMENT_AUTHORIZATION_ACCEPTED,
            JoorneyOrder::ORDER_STATUT_4_ORDER_VALIDATED,
            JoorneyOrder::ORDER_STATUT_5_PREPARATION
        ]);
    }

    /**
     * Set cart
     *
     * @param \stdClass $cart
     *
     * @return PlanooOrder
     */
    public function setCart($cart) {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Get cart
     *
     * @return \stdClass
     */
    public function getCart() {
        return $this->cart;
    }

    public function getUser() {
        return $this->user;
    }

    public function getPayment() {
        return $this->payment;
    }

    public function getBusinessPlan() {
        return $this->businessPlan;
    }

    public function getCatalog() {
        return $this->catalog;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function setPayment($payment) {
        $this->payment = $payment;
    }

    public function setBusinessPlan($businessPlan) {
        $this->businessPlan = $businessPlan;
    }

    public function setCatalog($catalog) {
        $this->catalog = $catalog;
    }

    public function getPreferedTime() {
        return $this->preferedTime;
    }

    public function setPreferedTime($preferedTime) {
        $this->preferedTime = $preferedTime;
    }

    public function getActivityField() {
        return $this->activityField;
    }

    public function getActivityDescription() {
        return $this->activityDescription;
    }

    public function setActivityField($activityField) {
        $this->activityField = $activityField;
    }

    public function setActivityDescription($activityDescription) {
        $this->activityDescription = $activityDescription;
    }

    public function getHash() {
        return $this->hash;
    }

    public function setHash($hash) {
        $this->hash = $hash;
    }

    public function getFilename() {
        return $this->filename;
    }

    public function getFile() {
        return $this->file;
    }

    public function setFilename($filename) {
        $this->filename = $filename;
    }

    public function setFile($file) {
        $this->file = $file;
    }

    public function upload() {
        if (null === $this->file) {
            return;
        }
        $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());
        $this->filename = $this->file->getClientOriginalName();
    }

    public function getUploadDir() {
        return "uploads/attachment";
    }

    public function getWebPath() {
        if (null === $this->filename) {
            return null;
        }

        return $this->getUploadDir() . '/' . $this->filename;
    }

    public function getUploadRootDir() {
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    public function getFunding() {
        return $this->funding;
    }

    public function setFunding($funding) {
        $this->funding = $funding;
    }

}
