<?php

namespace PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RequestInvoice
 *
 * ORM\Table(name="bp_wording_customwriting")
 * ORM\Entity(repositoryClass="BPBundle\Repository\CustomWritingRepository")
 */
class RequestInvoice {

    /**
     * @var int
     *
     * ORM\Column(name="id", type="integer")
     * ORM\Id
     * ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * ORM\Column(name="company", type="string", length=255)
     */
    private $company;

    /**
     * @var Address
     * ORM\OneToOne(targetEntity="BPBundle\Entity\Address", cascade={"persist", "remove"})
     */
    protected $address;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set company
     *
     * @param string $company
     *
     * @return RequestInvoice
     */
    public function setCompany($company) {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany() {
        return $this->company;
    }


    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }


}
