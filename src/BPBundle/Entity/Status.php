<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use UserBundle\Entity\SocialCharge;

/**
 * Status
 *
 * @ORM\Table(name="bp_status")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\StatusRepository")
 */
class Status
{
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Staff
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Staff", mappedBy="status", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $staffs;

    /**
     * @var SocialCharge
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\SocialCharge", mappedBy="status", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $chargesSociales;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->staffs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->eligibiliteCice = 0;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add staff
     *
     * @param \BPBundle\Entity\Staff $staff
     *
     * @return Status
     */
    public function addStaff(\BPBundle\Entity\Staff $staff)
    {
        $this->staffs[] = $staff;

        return $this;
    }

    /**
     * Remove staff
     *
     * @param \BPBundle\Entity\Staff $staff
     */
    public function removeStaff(\BPBundle\Entity\Staff $staff)
    {
        $this->staffs->removeElement($staff);
    }

    /**
     * Get staffs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStaffs()
    {
        return $this->staffs;
    }

    /**
     * Set chargesSociales
     *
     * @param \UserBundle\Entity\SocialCharge $chargesSociales
     *
     * @return Status
     */
    public function setChargesSociales(\UserBundle\Entity\SocialCharge $chargesSociales = null)
    {
        $this->chargesSociales = $chargesSociales;

        return $this;
    }

    /**
     * Get chargesSociales
     *
     * @return \UserBundle\Entity\SocialCharge
     */
    public function getChargesSociales()
    {
        return $this->chargesSociales;
    }
}
