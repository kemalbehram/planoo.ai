<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * InfoProduct
 *
 * @ORM\Table(name="bp_info_product")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\InfoProductRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class InfoProduct {

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
     * @ORM\Column(name="nbVente", type="float", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le nombre de vente doit être supérieur ou égal à 0",
     *      groups={"income"}
     * )
     */
    private $nbVente;

    /**
     * @var float
     *
     * @ORM\Column(name="prixVente", type="float", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le prix de vente doit être supérieur ou égal à 0",
     *      groups={"income"}
     * )
     */
    private $prixVente;

    /**
     * @var float
     *
     * @ORM\Column(name="caExercice", type="float", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le chiffre d'affaire du produit doit être supérieur ou égal à 0",
     *      groups={"income"}
     * )
     */
    private $CAExercice;

    /**
     * @var float
     *
     * @ORM\Column(name="cout", type="float", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le coût doit être supérieur ou égal à 0",
     *      groups={"income"}
     * )
     */
    private $cout;

    /**
     * @var float
     *
     * @ORM\Column(name="coutExercice", type="float", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le coût de revient du produit doit être supérieur ou égal à 0",
     *      groups={"income"}
     * )
     */
    private $coutExercice;

    /**
     * @var float
     *
     * @ORM\Column(name="marge", type="float", nullable=false)
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "Le taux de marge doit être supérieur ou égal à 0%",
     *      maxMessage = "Le taux de marge doit être inférieur ou égal à 100%",
     *      groups={"income"}
     * )
     */
    private $marge;

    /**
     * @var float
     *
     * @ORM\Column(name="commission", type="float", nullable=true)
     * @Assert\NotBlank(message="attention",groups={"income"})
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "La commission doit être comprise entre 0 et 100%",
     *      maxMessage = "La commission doit être comprise entre 0 et 100%",
     *      groups={"income"})
     */
    private $commission;

    /**
     * @var Produit
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="infoProduct")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\Exercice", inversedBy="infoProduct", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $exercice;

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
    protected $caMensuel = [];
    protected $coutMensuel = [];
    protected $commissionMensuel = [];

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nbVente
     *
     * @param integer $nbVente
     *
     * @return InfoProduct
     */
    public function setNbVente($nbVente) {
        $this->nbVente = $nbVente;

        return $this;
    }

    /**
     * Get nbVente
     *
     * @return integer
     */
    public function getNbVente() {
        return $this->nbVente;
    }

    /**
     * Set prixVente
     *
     * @param float $prixVente
     *
     * @return InfoProduct
     */
    public function setPrixVente($prixVente) {
        $this->prixVente = $prixVente;

        return $this;
    }

    /**
     * Get prixVente
     *
     * @return float
     */
    public function getPrixVente() {
        return $this->prixVente;
    }

    /**
     * Set cout
     *
     * @param float $cout
     *
     * @return InfoProduct
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
     * Set commission
     *
     * @param float $commission
     *
     * @return InfoProduct
     */
    public function setCommission($commission) {
        $this->commission = $commission;

        return $this;
    }

    /**
     * Get commission
     *
     * @return float
     */
    public function getCommission() {
        return $this->commission;
    }

    /**
     * Set exercice
     *
     * @param \BPBundle\Entity\Exercice $exercice
     *
     * @return InfoProduct
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
     * Set produit
     *
     * @param \BPBundle\Entity\Produit $produit
     *
     * @return InfoProduct
     */
    public function setProduit(\BPBundle\Entity\Produit $produit = null) {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \BPBundle\Entity\Produit
     */
    public function getProduit() {
        return $this->produit;
    }

    /**
     * @return mixed
     */
    public function getCaMensuel() {
        return $this->caMensuel;
    }

    /**
     * @param mixed $caMensuel
     * @return InfoProduct
     */
    public function addCaMensuel(\DateTime $date, $caMensuel) {
        $formatKey = $date->format('Y-m-d');
        if (array_key_exists($formatKey, $this->caMensuel)) {
            $this->caMensuel[$formatKey] += $caMensuel;
        } else {
            $this->caMensuel[$formatKey] = $caMensuel;
        }

        return $this;
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

    /**
     * @return mixed
     */
    public function getCommissionMensuel() {
        return $this->commissionMensuel;
    }

    /**
     * @param mixed $commissionMensuel
     * @return InfoProduct
     */
    public function addCommissionMensuel(\DateTime $date, $commissionMensuel) {
        $formatKey = $date->format('Y-m-d');
        if (array_key_exists($formatKey, $this->commissionMensuel)) {
            $this->commissionMensuel[$formatKey] += $commissionMensuel;
        } else {
            $this->commissionMensuel[$formatKey] = $commissionMensuel;
        }

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

    function getCAExercice() {
        return $this->CAExercice;
    }

    function getCoutExercice() {
        return $this->coutExercice;
    }

    function setCAExercice($CAExercice) {
        $this->CAExercice = $CAExercice;
    }

    function setCoutExercice($coutExercice) {
        $this->coutExercice = $coutExercice;
    }

    public function getMarge() {
        return $this->marge;
    }

    public function setMarge($marge) {
        $this->marge = $marge;
    }

}
