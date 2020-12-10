<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;


/**
 * Concept
 *
 * @ORM\Table(name="back_concept")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\ConceptRepository")
 */
class Concept
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
     * @var Definition
     * @ORM\OneToMany(targetEntity="Definition", mappedBy="concept", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $definitions;


    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=255, nullable=true)
     */
    private $video;


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
     * Set video
     *
     * @param string $video
     *
     * @return Concept
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string
     */
    public function getVideo()
    {
        return $this->video;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->definitions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add definition
     *
     * @param \BackBundle\Entity\Definition $definition
     *
     * @return Concept
     */
    public function addDefinition(\BackBundle\Entity\Definition $definition)
    {
        $this->definitions[] = $definition;

        return $this;
    }

    /**
     * Remove definition
     *
     * @param \BackBundle\Entity\Definition $definition
     */
    public function removeDefinition(\BackBundle\Entity\Definition $definition)
    {
        $this->definitions->removeElement($definition);
    }

    /**
     * Get definitions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }
}
