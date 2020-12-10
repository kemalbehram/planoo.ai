<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * InfoCharge
 *
 * @ORM\Table(name="bp_info_charge")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\InfoChargeRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class InfoCharge {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="cout", type="float", nullable=true)
     */
    private $cout;

    /**
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\Exercice", inversedBy="infoCharge", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $exercice;

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
     * @var Charge
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\Charge", inversedBy="infoCharges", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $charge;
    protected $coutMensuel = [];

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set cout
     *
     * @param float $cout
     *
     * @return InfoCharge
     */
    public function setCout($cout) {
        $this->cout = $cout;

        return $this;
    }

    /**
     * Get cout
     *
     * @return float
     */
    public function getCout() {
        return $this->cout;
    }

    /**
     * Set charge
     *
     * @param \BPBundle\Entity\Charge $charge
     *
     * @return InfoCharge
     */
    public function setCharge(\BPBundle\Entity\Charge $charge = null) {
        $this->charge = $charge;

        return $this;
    }

    /**
     * Get charge
     *
     * @return \BPBundle\Entity\Charge
     */
    public function getCharge() {
        return $this->charge;
    }

    /**
     * Set exercice
     *
     * @param \BPBundle\Entity\Exercice $exercice
     *
     * @return InfoCharge
     */
    public function setExercice(\BPBundle\Entity\Exercice $exercice = null) {
        $this->exercice = $exercice;

        return $this;
    }

    /**
     * Get exercice
     *
     * @return \BPBundle\Entity\Exercice
     */
    public function getExercice() {
        return $this->exercice;
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

    /**
     * @return mixed
     */
    public function getCoutMensuel() {
        return $this->coutMensuel;
    }

    /**
     * @param mixed $coutMensuel
     * @return InfoProduct
     */
    public function addCoutMensuel(\DateTime $date, $coutMensuel) {
        $formatKey = $date->format('Y-m-d');
        if (array_key_exists($formatKey, $this->coutMensuel)) {
            $this->coutMensuel[$formatKey] += $coutMensuel;
        } else {
            $this->coutMensuel[$formatKey] = $coutMensuel;
        }

        return $this;
    }

}
