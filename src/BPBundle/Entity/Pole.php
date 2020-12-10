<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Pole
 *
 * @ORM\Table(name="bp_pole_staff")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\PoleRepository")
 */
class Pole
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
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Staff", mappedBy="pole", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $staffs;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->staffs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add staff
     *
     * @param \BPBundle\Entity\Staff $staff
     *
     * @return Pole
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
}
