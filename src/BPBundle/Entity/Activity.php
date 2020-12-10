<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activity
 *
 * @ORM\Table(name="bp_activity")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\ActivityRepository")
 */
class Activity {

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
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isMarketStudyAvailable", type="boolean")
     */
    private $isMarketStudyAvailable;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Activity
     */
    public function setCategory($category) {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }

    public function getLabel() {
        return $this->category . ' - ' . $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Activity
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

    public function getIsMarketStudyAvailable() {
        return $this->isMarketStudyAvailable;
    }

    public function setIsMarketStudyAvailable($isMarketStudyAvailable) {
        $this->isMarketStudyAvailable = $isMarketStudyAvailable;
    }

}
