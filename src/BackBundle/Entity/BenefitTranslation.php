<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BenefitTranslation
 * @ORM\Table(name="back_benefit_translation")
 * @ORM\Entity
 */
class BenefitTranslation
{
    use ORMBehaviors\Translatable\Translation;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="text")
     */
    private $detail;

    /**
     * @var string
     *
     * @ORM\Column(name="postScriptum", type="string", length=255)
     */
    private $postScriptum;



    /**
     * Set name
     *
     * @param string $name
     *
     * @return BenefitTranslation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set detail
     *
     * @param string $detail
     *
     * @return BenefitTranslation
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set postScriptum
     *
     * @param string $postScriptum
     *
     * @return BenefitTranslation
     */
    public function setPostScriptum($postScriptum)
    {
        $this->postScriptum = $postScriptum;

        return $this;
    }

    /**
     * Get postScriptum
     *
     * @return string
     */
    public function getPostScriptum()
    {
        return $this->postScriptum;
    }
}
