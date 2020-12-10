<?php

namespace PaymentBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use PaymentBundle\Entity\Cart;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Payment
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="PaymentBundle\Repository\PaymentRepository")
 */
class Payment {

    const PAYMENT_STATUT_INITIATE = 'Initialisé';
    const PAYMENT_STATUT_CAPTURED = 'Paiement accepté';
    const PAYMENT_STATUT_FINISH = 'Paiement réalisé';
    const PAYMENT_STATUT_CANCELED = 'Paiement annulé';

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
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 16,
     *      max = 19
     * )
     */
    private $card;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 200
     * )
     */
    private $name;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 1,
     *      max = 12
     * )
     */
    private $month;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0,
     *      max = 99
     * )
     */
    private $year;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0,
     *      max = 999
     * )
     */
    private $cvc;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="stripe_token", type="string", length=255)
     */
    private $stripeToken;

    /**
     * @ORM\OneToOne(targetEntity="Cart", inversedBy="payment", cascade={"persist"})
     * @ORM\JoinColumn(name="cart_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
     */
    protected $cart;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="paymentDate", type="datetime")
     */
    private $paymentDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="processingDate", type="datetime", nullable=true,options={"default"=null})
     */
    private $processingDate;

    /**
     * @ORM\Column(name="statut", type="string", nullable=false)
     */
    protected $statut;

    /**
     * @var bool
     *
     * @ORM\Column(name="invoiceSent", type="boolean",options={"default"=false})
     */
    private $invoiceSent = false;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set card
     *
     * @param string $card
     *
     * @return Payment
     */
    public function setCard($card) {
        $this->card = $card;

        return $this;
    }

    /**
     * Get card
     *
     * @return string
     */
    public function getCard() {
        return $this->card;
    }

    public function getPaymentDate() {
        return $this->paymentDate;
    }

    public function setPaymentDate(DateTime $paymentDate) {
        $this->paymentDate = $paymentDate;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Payment
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
     * Set month
     *
     * @param string $month
     *
     * @return Payment
     */
    public function setMonth($month) {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return string
     */
    public function getMonth() {
        return $this->month;
    }

    /**
     * Set year
     *
     * @param string $year
     *
     * @return Payment
     */
    public function setYear($year) {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear() {
        return $this->year;
    }

    /**
     * Set cvc
     *
     * @param string $cvc
     *
     * @return Payment
     */
    public function setCvc($cvc) {
        $this->cvc = $cvc;

        return $this;
    }

    /**
     * Get cvc
     *
     * @return string
     */
    public function getCvc() {
        return $this->cvc;
    }

    /**
     * Set stripeToken
     *
     * @param string $stripeToken
     *
     * @return Payment
     */
    public function setStripeToken($stripeToken) {
        $this->stripeToken = $stripeToken;

        return $this;
    }

    /**
     * Get stripeToken
     *
     * @return string
     */
    public function getStripeToken() {
        return $this->stripeToken;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return Payment
     */
    public function setAmount($amount) {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * Set cart
     *
     * @param Cart $cart
     *
     * @return Payment
     */
    public function setCart($cart) {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Get cart
     *
     * @return Cart
     */
    public function getCart() {
        return $this->cart;
    }

    public function getProcessingDate() {
        return $this->processingDate;
    }

    public function setProcessingDate(DateTime $processingDate) {
        $this->processingDate = $processingDate;
    }

    public function getStatut() {
        return $this->statut;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
    }


    /**
     * Get the value of invoiceSent
     *
     * @return  bool
     */
    public function getInvoiceSent() {
        return $this->invoiceSent;
    }

    /**
     * Set the value of invoiceSent
     *
     * @param  bool  $invoiceSent
     *
     * @return  self
     */
    public function setInvoiceSent(bool $invoiceSent) {
        $this->invoiceSent = $invoiceSent;

        return $this;
    }
}
