<?php

/**
 * Created by PhpStorm.
 * User: wac28
 * Date: 26/09/16
 * Time: 11:36
 */

namespace BPBundle\Controller\Frontend;

use BPBundle\Form\FundingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use BPBundle\Entity\Funding;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class FundingController extends Controller {

    // Generate an array contains a key -> value with the errors where the key is the name of the form field
    protected function getErrorMessages(\Symfony\Component\Form\Form $form) {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }

    /**
     * Show the corrects fields according the select choice
     * @param Request $request
     * @param $id
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function ajaxShowCorrectFieldsByLabelAction(Request $request, $id) {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $chargeLabel = $em->getRepository('BPBundle:ChargeLabel')->find($id);
            if (!$chargeLabel) {
                return new JsonResponse(["errorMessage" => "Il n'y as pas de charge label correspondante"], 500);
            }

            return new JsonResponse([
                'displayTaux' => $chargeLabel->getDisplayTaux(),
                'displayDuree' => $chargeLabel->getDisplayDuree()
            ]);
        }

        return $this->redirectToRoute('financial_funding_index');
    }

    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $funding = $em->getRepository('BPBundle:Funding')->find($id);

        //check permissions
        $this->denyAccessUnlessGranted(null, $funding->getBusinessPlan());

        $options = [
            'entityManager' => $em
        ];

        $form = $this->createForm(FundingType::class, $funding, $options);

        $form->submit($request->request->get($form->getName()));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($funding);
            $em->flush();
            return new JsonResponse(array(
                'code' => 200,
                'message' => 'OK',
                'errors' => array('errors' => array(''))), 200);
        }

        return new JsonResponse(array(
            'code' => 400,
            'message' => 'Invalid Form',
            'errors' => $this->getErrorMessages($form)), 400);
    }

    public function createAction(Request $request, $idBusinessPlan) {
        $em = $this->getDoctrine()->getManager();
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $options = [
            'entityManager' => $em
        ];

        // Création d'une nouvelle source de revenu
        $funding = new Funding();
        $funding->setBusinessPlan($bp);

        $form = $this->createForm(FundingType::class, $funding, $options);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($funding);
            $em->flush();

            return new JsonResponse(array(
                'code' => 201,
                'id' => $funding->getId(),
                'message' => 'OK',
                'errors' => array('errors' => array(''))), 200);
        }

        return new JsonResponse(array(
            'code' => 400,
            'message' => 'Invalid Form',
            'errors' => $this->getErrorMessages($form)), 400);
    }

    public function showAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $funding = $em->getRepository('BPBundle:Funding')->find($id);

        if ($funding) {
            //check permissions
            $this->denyAccessUnlessGranted(null, $funding->getBusinessPlan());

            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            return new JsonResponse(array(
                'code' => 201,
                'data' => $serializer->serialize($funding, 'json'),
                'message' => 'OK',
                'errors' => array('errors' => array(''))), 200);
        } else {
            return new JsonResponse(array(
                'code' => 404,
                'id' => $id,
                'message' => 'Object not found')
            );
        }
    }

    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $funding = $em->getRepository('BPBundle:Funding')->find($id);

        //check permissions
        $this->denyAccessUnlessGranted(null, $funding->getBusinessPlan());

        $em->remove($funding);
        $em->flush();


        return new JsonResponse(array(
            'code' => 200,
            'message' => 'OK',
            'errors' => array('errors' => array(''))), 200);
    }

    public function listAction(Request $request) {

        // Recuperation du BP
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $historyFormSession = $session->get('bp_formulaire');
        $idBusinessPlan = $historyFormSession['business_plan'];
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        if ($request->getRequestFormat() === 'json') {
            return new JsonResponse(array(
                'code' => 200,
                'data' => $serializer->serialize($bp->getFundings(), 'json'),
                'message' => 'OK',
                'errors' => array('errors' => array(''))), 200);
        } elseif ($request->getRequestFormat() === 'html') {
            return $this->render('BPBundle:Frontend:Funding/list.html.twig', [
                        'fundings' => $bp->getFundings(),
                        'accountingNbPeriod' => $bp->getInformation()->getAccountingPeriod(),
                        'accountingDate' => $bp->getInformation()->getClosingDate(),
            ]);
        }
    }

    public function getEditFormAction(Request $request, $id = null) {
        $em = $this->getDoctrine()->getManager();
        if ($id) {
            $funding = $em->getRepository('BPBundle:Funding')->find($id);
            $action = 'update';
        } elseif ($request->get('funding')['id']) {
            $id = $request->get('funding')['id'];
            $funding = $em->getRepository('BPBundle:Funding')->find($id);
            $action = 'update';
        } else {
            $session = $request->getSession();
            $historyFormSession = $session->get('bp_formulaire');
            $idBusinessPlan = $historyFormSession['business_plan'];
            $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);
            $funding = new Funding();
            $funding->setBusinessPlan($bp);
            $action = 'create';
        }

        //check permissions
        $this->denyAccessUnlessGranted(null, $funding->getBusinessPlan());

        $options = [
            'entityManager' => $em
        ];

        $form = $this->createForm(FundingType::class, $funding, $options);
        $form->handleRequest($request);

        return $this->render('BPBundle:Frontend:Funding/edit.html.twig', [
                    'editForm' => $form->createView(),
                    'action' => $action,
                    'idFunding' => $form->getData()->getId(),
                    'idBusinessPlan' => $funding->getBusinessPlan()->getId(),
                    'displayTaux' => $form->getData()->getChargeLabel()->getDisplayTaux(),
                    'displayDuree' => $form->getData()->getChargeLabel()->getDisplayDuree()
        ]);
    }

    public function getAddFormAction(Request $request, $idBusinessPlan) {
        $em = $this->getDoctrine()->getManager();
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);
        $chargeLabel = $em->getRepository('BPBundle:ChargeLabel')->findOneBy(['type' => 'financement']);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $funding = new Funding();
        $funding->setBusinessPlan($bp);
        $funding->setChargeLabel($chargeLabel);

        $options = [
            'entityManager' => $em
        ];

        $form = $this->createForm(FundingType::class, $funding, $options);

        $form->handleRequest($request);

        return $this->render('BPBundle:Frontend:Funding/edit.html.twig', [
                    'produit' => $funding,
                    'editForm' => $form->createView(),
                    'action' => 'create',
                    'idFunding' => 0,
                    'idBusinessPlan' => $funding->getBusinessPlan()->getId(),
                    'displayTaux' => true,
                    'displayDuree' => true
        ]);
    }

}
