<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Benefit
 *
 * @ORM\Table(name="back_benefit")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\BenefitRepository")
 */
class Benefit {

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
     * @var Catalog
     * @ORM\OneToOne(targetEntity="PromotionBundle\Entity\Catalog", cascade={"persist"})
     */
    private $catalog;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    public function getCatalog() {
        return $this->catalog;
    }

    public function setCatalog($catalog) {
        $this->catalog = $catalog;
    }

}
