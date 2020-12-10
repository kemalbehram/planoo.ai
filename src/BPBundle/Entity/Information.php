<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Information
 *
 * @ORM\Table(name="bp_information")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\InformationRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Information {

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
     * @ORM\Column(name="name_corporate", type="string", length=100)
     * @Assert\NotBlank(message="attention",groups={"information"})
     */
    protected $nameCorporate;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname_owner", type="string", length=100)
     * @Assert\NotBlank(message="attention",groups={"information"})
     */
    protected $firstnameOwner;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname_owner", type="string", length=100)
     * @Assert\NotBlank(message="attention",groups={"information"})
     */
    protected $lastnameOwner;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    protected $logo;

    /**
     *
     * @Assert\Image(
     *     maxSize = "1M",
     *     mimeTypes = {"image/jpg", "image/jpeg","image/gif","image/png" },
     *     mimeTypesMessage = "Extensions autorisées jpeg,jpg,gif,png",
     *     maxSizeMessage = "Merci d'uploader un fichier inférieur ou à égal à 1Mo",
     *     groups={"information"}
     * )
     */
    protected $file;

    /**
     * @var string
     *
     * @ORM\Column(name="job", type="string", length=150, nullable=true)
     */
    protected $job;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\Activity")
     * @Assert\NotBlank(message="attention",groups={"information"})
     */
    protected $activity;

    /**
     * @var string
     *
     * @ORM\Column(name="activityDetail", type="string", length=150, nullable=false,options={"default"=" "})
     */
    protected $activityDetail;

    /**
     * @var string
     *
     * @ORM\Column(name="franchise", type="boolean",  options={"default"=0})
     * @Assert\NotBlank(message="attention",groups={"information"})
     */
    protected $franchise;

    /**
     * @var string
     *
     * @ORM\Column(name="isIR", type="boolean",  options={"default"=0})
     * @Assert\NotBlank(message="attention",groups={"information"})
     */
    protected $ir;

    /**
     * @var string
     *
     * @ORM\Column(name="name_franchise", type="string", length=150, nullable=true)
     */
    protected $nameFranchise;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\Location", cascade={"persist"})
     * @Assert\NotBlank(message="attention",groups={"information"})
     */
    protected $location;

    /**
     * @var float
     *
     * @ORM\Column(name="capital", type="float", nullable=true)
     * @Assert\NotBlank(message="attention",groups={"finance"})
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "Le capital doit être supérieur à 1€",
     *      groups={"finance"}
     * )
     */
    protected $capital;

    /**
     * @var integer
     *
     * @ORM\Column(name="accounting_period", type="integer", length=2, nullable=true)
     * @Assert\NotBlank(message="attention",groups={"finance"})
     * @Assert\Range(
     *      min = 3,
     *      max = 5,
     *      minMessage = "Le nombre d'exercices doit être compris entre 3 et 5",
     *      maxMessage = "Le nombre d'exercices doit être compris entre 3 et 5",
     *      groups={"finance"}
     * )
     */
    protected $accountingPeriod;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Assert\NotBlank(message="attention",groups={"finance"})
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="closing_date", type="datetime", nullable=true)
     * @Assert\NotBlank(message="attention",groups={"finance"})
     */
    protected $closingDate;

    /**
     * @var float
     *
     * @ORM\Column(name="tva", type="float", length=255, nullable=true)
     * @Assert\NotBlank(message="attention",groups={"finance"})
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "La TVA doit être comprise entre 0 et 100%",
     *      maxMessage = "La TVA doit être comprise entre 0 et 100%",
     *      groups={"finance"}
     * )
     */
    protected $tva;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tvaSurEncaissement", type="boolean",  options={"default"=0})
     */
    protected $tvaSurEncaissement;

    /**
     * @var Address
     * @ORM\OneToOne(targetEntity="BPBundle\Entity\Address", cascade={"persist", "remove"})
     */
    protected $address;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="informations")
     */
    protected $user;

    /**
     * @ORM\OneToOne(targetEntity="BusinessPlan", inversedBy="information", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="businessPlan_id", referencedColumnName="id",onDelete="CASCADE")
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
    protected $informationCreatedAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->closingDate = new \DateTime('last day of december');
        $this->tva = 20;
        $this->franchise = false;
        $this->tvaSurEncaissement = false;
    }

    /**
     * @var LegalForm
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\LegalForm", inversedBy="informations", cascade={"persist"})
     */
    protected $legalForm;

    public function getFile() {
        return $this->file;
    }

    public function setFile($file) {
        $this->file = $file;
    }

    public function upload() {
        if (null === $this->file) {
            return;
        }
        $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());
        $this->logo = $this->file->getClientOriginalName();
    }

    public function getUploadDir() {
        return "uploads/logos/";
    }

    public function getWebPath() {
        if (null === $this->logo) {
            return null;
        }

        return $this->getUploadDir() . '/' . $this->logo;
    }

    public function getUploadRootDir() {
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    public function getTvaSurEncaissement() {
        return $this->tvaSurEncaissement;
    }

    public function setTvaSurEncaissement($tvaSurEncaissement) {
        $this->tvaSurEncaissement = $tvaSurEncaissement;
    }

    /**
     * Set nameCorporate
     *
     * @param string $nameCorporate
     *
     * @return Information
     */
    public function setNameCorporate($nameCorporate) {
        $this->nameCorporate = $nameCorporate;

        return $this;
    }

    /**
     * Get nameCorporate
     *
     * @return string
     */
    public function getNameCorporate() {
        return $this->nameCorporate;
    }

    /**
     * Set firstnameOwner
     *
     * @param string $firstnameOwner
     *
     * @return Information
     */
    public function setFirstnameOwner($firstnameOwner) {
        $this->firstnameOwner = $firstnameOwner;

        return $this;
    }

    /**
     * Get firstnameOwner
     *
     * @return string
     */
    public function getFirstnameOwner() {
        return $this->firstnameOwner;
    }

    /**
     * Set lastnameOwner
     *
     * @param string $lastnameOwner
     *
     * @return Information
     */
    public function setLastnameOwner($lastnameOwner) {
        $this->lastnameOwner = $lastnameOwner;

        return $this;
    }

    /**
     * Get lastnameOwner
     *
     * @return string
     */
    public function getLastnameOwner() {
        return $this->lastnameOwner;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Information
     */
    public function setLogo($logo) {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo() {
        return $this->logo;
    }

    /**
     * Set job
     *
     * @param string $job
     *
     * @return Information
     */
    public function setJob($job) {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return string
     */
    public function getJob() {
        return $this->job;
    }

    /**
     * Set franchise
     *
     * @param string $franchise
     *
     * @return Information
     */
    public function setFranchise($franchise) {
        $this->franchise = $franchise;

        return $this;
    }

    /**
     * Get franchise
     *
     * @return string
     */
    public function getFranchise() {
        return $this->franchise;
    }

    /**
     * Set or
     *
     * @param boolean $ir
     *
     * @return Information
     */
    public function setIr($ir) {
        $this->ir = $ir;

        return $this;
    }

    /**
     * Get ir
     *
     * @return boolean 
     */
    public function getIr() {
        return $this->ir;
    }

    /**
     * Set nameFranchise
     *
     * @param string $nameFranchise
     *
     * @return Information
     */
    public function setNameFranchise($nameFranchise) {
        $this->nameFranchise = $nameFranchise;

        return $this;
    }

    /**
     * Get nameFranchise
     *
     * @return string
     */
    public function getNameFranchise() {
        return $this->nameFranchise;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return Information
     */
    public function setLocation($location) {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Set capital
     *
     * @param float $capital
     *
     * @return Information
     */
    public function setCapital($capital) {
        $this->capital = $capital;

        return $this;
    }

    /**
     * Get capital
     *
     * @return float
     */
    public function getCapital() {
        return $this->capital;
    }

    /**
     * Set accountingPeriod
     *
     * @param string $accountingPeriod
     *
     * @return Information
     */
    public function setAccountingPeriod($accountingPeriod) {
        $this->accountingPeriod = $accountingPeriod;

        return $this;
    }

    /**
     * Get accountingPeriod
     *
     * @return string
     */
    public function getAccountingPeriod() {
        return $this->accountingPeriod;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Information
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
     * Set closingDate
     *
     * @param \DateTime $closingDate
     *
     * @return Information
     */
    public function setClosingDate($closingDate) {
        $this->closingDate = $closingDate;

        return $this;
    }

    /**
     * Get closingDate
     *
     * @return \DateTime
     */
    public function getClosingDate() {
        return $this->closingDate;
    }

    /**
     * Set tva
     *
     * @param string $tva
     *
     * @return Information
     */
    public function setTva($tva) {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Get tva
     *
     * @return string
     */
    public function getTva() {
        return $this->tva;
    }

    /**
     * Set address
     *
     * @param \BPBundle\Entity\Address $address
     *
     * @return Information
     */
    public function setAddress(\BPBundle\Entity\Address $address = null) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \BPBundle\Entity\Address
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Information
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
     * Set businessPlan
     *
     * @param \BPBundle\Entity\BusinessPlan $businessPlan
     *
     * @return Information
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
     * Set legalForm
     *
     * @param \BPBundle\Entity\LegalForm $legalForm
     *
     * @return Information
     */
    public function setLegalForm(\BPBundle\Entity\LegalForm $legalForm = null) {
        $this->legalForm = $legalForm;

        return $this;
    }

    /**
     * Get legalForm
     *
     * @return \BPBundle\Entity\LegalForm
     */
    public function getLegalForm() {
        return $this->legalForm;
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
     * @param \DateTime $informationCreatedAt
     *
     * @return BusinessPlan
     */
    public function setInformationCreatedAt($informationCreatedAt) {
        $this->informationCreatedAt = $informationCreatedAt;

        return $this;
    }

    /**
     * Get informationCreatedAt
     *
     * @return \DateTime
     */
    public function getInformationCreatedAt() {
        return $this->informationCreatedAt;
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

    public function getActivity() {
        return $this->activity;
    }

    public function setActivity($activity) {
        $this->activity = $activity;
    }

    public function getActivityDetail() {
        return $this->activityDetail;
    }

    public function setActivityDetail($activityDetail) {
        $this->activityDetail = $activityDetail;
    }

}
