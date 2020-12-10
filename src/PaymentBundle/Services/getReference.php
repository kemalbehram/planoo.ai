<?php


namespace FrontBundle\Services;

use Symfony\Component\Security\Core\SecurityContextInterface;

class getReference
{
    public function __construct($securityContext, $entityManager)
    {
        $this->securityContext = $securityContext;
        $this->em = $entityManager;
    }

    public function reference()
    {
        $reference = $this->em->getRepository('BPBundle:BusinessPlan')->findOneBy(
            [
                'validate' => 1
            ],
            [
                'id' => 'DESC'
            ],
            1,1);

        if (!$reference)
            return 1;
        else
            return $reference->getReference() +1;
    }
}