<?php

namespace BPBundle\Security;

use BPBundle\Entity\BusinessPlan;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BusinessPlanVoter
 *
 * @author franc
 */
class BusinessPlanVoter extends Voter {

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager) {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject) {

        // only vote on Post objects inside this voter
        if (!$subject instanceof BusinessPlan) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token) {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // ROLE_ADMIN can do anything! The power!
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return true;
        }

        $bp = $subject;

        if ($this->decisionManager->decide($token, array('ROLE_PARTNER')) && $user->getPartner() === $bp->getUser()->getPartner()) {
            return true;
        }

        return $user === $bp->getUser();
    }

}
