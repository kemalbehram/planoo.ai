<?php

/**
 * Created by PhpStorm.
 * User: wac28
 * Date: 10/10/16
 * Time: 16:56
 */

namespace BPBundle\Controller\Frontend;

use BPBundle\Form\InformationType;
use PromotionBundle\Entity\Catalog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BPBundle\Entity\Information;
use BPBundle\Entity\Bfr;
use Symfony\Component\HttpFoundation\JsonResponse;
use BPBundle\Entity\LegalForm;
use BPBundle\Entity\Activity;

class InformationController extends Controller {

    public function editAction(Request $request, $hash) {
        $em = $this->getDoctrine()->getManager();
        if (!$this->getUser()) {
            return $this->redirectToRoute('user_my_projects');
        }

        // BP
        $bp = $em->getRepository('BPBundle:BusinessPlan')->findOneBy(['hash' => $hash]);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        if (!$information = $bp->getInformation()) {
            $information = new Information();
            $information->setBusinessPlan($bp);
        }

        // GESTION DES ELEMENTS DE Session
        $session = $request->getSession();

        // Bp_formulaire
        $session->remove('bp_formulaire');
        $session->set('bp_formulaire', array());
        $historyFormSession = $session->get('bp_formulaire');
        $currentStepRoute = 'financial_information_index';

        $historyFormSession['step'] = $bp->getSteps();
        $historyFormSession['business_plan'] = $bp->getId();
        $historyFormSession['currentStepRoute'] = $currentStepRoute;

        $session->set('bp_formulaire', $historyFormSession);

        $options = [
            'entityManager' => $em
        ];

        // GESTION DU FORMULAIRE
        $form = $this->createForm(InformationType::class, $information, $options);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bp->addStep('1');
            $em->persist($bp);
            $information->upload();
            $em->persist($information);
            if (!$bp->getInfoBfr()) {
                $bfr = new Bfr();
                $bfr->setUser($this->getUser());
                $bfr->setBusinessPlan($bp);
                $em->persist($bfr);
            }
            $em->flush();
            $currentStepRoute = "financial_financiere_index";
            $historyFormSession['step'] = $bp->getSteps();
            $historyFormSession['currentStepRoute'] = $currentStepRoute;
            $historyFormSession['business_plan'] = $bp->getId();

            $session->set('bp_formulaire', $historyFormSession);

            return $this->redirectToRoute($currentStepRoute);
        }

        //die(dump($session));
        $infos_page = $em->getRepository('BPBundle:Page')->findOneBy(['step' => 1]);

        return $this->render('BPBundle:Frontend:Information/information.html.twig', [
                    'bp' => $bp,
                    'steps' => $bp->getSteps(),
                    'bpPaid' => $bp->getState() == 'validate',
                    'bpHash' => $bp->getHash(),
                    'infos_page' => $infos_page,
                    'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function ajaxCountryToLegalFormAction(Request $request) {
        if ($request->isXmlHttpRequest() && $request->query->get('id-country')) {
            $idCountry = $request->query->get('id-country');
            $em = $this->getDoctrine()->getManager();
            $legalForms = $em->getRepository('BPBundle:LegalForm')->findByCountry($idCountry);

            return new JsonResponse(['html' => $this->renderView('BPBundle:Frontend:LegalForm/renderview-option-select.html.twig', ['legalForms' => $legalForms])]);
        }

        return $this->redirectToRoute('user_my_projects');
    }

    public function ajaxLegalFormToIrAction(Request $request) {
        $idLegalForm = $request->query->get('id-legal-form');
        $em = $this->getDoctrine()->getManager();
        $legalForm = $em->getRepository('BPBundle:LegalForm')->findOneById($idLegalForm);

        return new JsonResponse(['isIR' => $legalForm->getIr(), 'isIS' => $legalForm->getIs()]);
    }

    public function ajaxActivityListAction(Request $request) {
        $activityCategory = $request->query->get('activityCategory');
        $em = $this->getDoctrine()->getManager();
        $activityList = $em->getRepository('BPBundle:Activity')->findBy(['category' => $activityCategory]);

        return new JsonResponse($activityList);
    }

    // /**
    //  * Gestion de tableau de session 
    //  * @param $session, $element
    //  */
    // private function isSessionElementExist($session, $element) {
    //     if (!$session->has($element))
    //         $session->set($element, array());
    //     return $session->get($element);
    // }

}
