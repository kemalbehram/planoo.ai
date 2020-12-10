<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Definition
 *
 * @ORM\Table(name="back_definition")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\DefinitionRepository")
 */
class Definition {

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
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @var Concept
     * @ORM\ManyToOne(targetEntity="Concept", inversedBy="definitions", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $concept;

    /**
     *
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"image/jpg", "image/jpeg","image/gif","image/png","image/svg+xml" },
     *     mimeTypesMessage = "Extensions autorisées jpeg,jpg,gif,png",
     *     maxSizeMessage = "Merci d'uploader un fichier inférieur ou à égal à 1000k"
     * )
     */
    private $file;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Definition
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
        return "uploads/testimonial";
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
     * Set concept
     *
     * @param \BackBundle\Entity\Concept $concept
     *
     * @return Definition
     */
    public function setConcept(\BackBundle\Entity\Concept $concept = null) {
        $this->concept = $concept;

        return $this;
    }

    /**
     * Get concept
     *
     * @return \BackBundle\Entity\Concept
     */
    public function getConcept() {
        return $this->concept;
    }

}
