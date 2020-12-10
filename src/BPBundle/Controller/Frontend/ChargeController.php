<?php

/**
 * Created by PhpStorm.
 * User: wac28
 * Date: 26/09/16
 * Time: 11:36
 */

namespace BPBundle\Controller\Frontend;

use BPBundle\Form\ChargeType;
use BPBundle\Entity\Charge;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ChargeController extends Controller {

    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $charge = $em->getRepository('BPBundle:Charge')->find($id);

        //check permissions
        $this->denyAccessUnlessGranted(null, $charge->getBusinessPlan());


        $isTaux = false;
        $isCustomLabel = false;
        if ($request->get('isTaux')) {
            $isTaux = $request->get('isTaux') === 'true';
        }
        if ($request->get('isCustomLabel')) {
            $isCustomLabel = $request->get('isCustomLabel') === 'true';
        }

        $options = ['attr' => [
                'isTaux' => $isTaux,
                'isCustomLabel' => $isCustomLabel
            ]
        ];
        $form = $this->createForm(ChargeType::class, $charge, $options);

        $form->submit($request->request->get($form->getName()));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($charge);
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

        $charge = new Charge();
        $charge->setBusinessPlan($bp);
        foreach ($bp->getExercices() as $exercice) {
            $charge->generateOneInfoCharge($exercice);
        }

        $isTaux = false;
        $isCustomLabel = false;
        if ($request->get('isTaux')) {
            $isTaux = $request->get('isTaux') === 'true';
        }
        if ($request->get('isCustomLabel')) {
            $isCustomLabel = $request->get('isCustomLabel') === 'true';
        }

        $options = ['attr' => [
                'isTaux' => $isTaux,
                'isCustomLabel' => $isCustomLabel
            ]
        ];
        $form = $this->createForm(ChargeType::class, $charge, $options);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($charge);
            $em->flush();

            return new JsonResponse(array(
                'code' => 201,
                'id' => $charge->getId(),
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
        $charge = $em->getRepository('BPBundle:Charge')->find($id);

        if ($charge) {
//check permissions
            $this->denyAccessUnlessGranted(null, $charge->getBusinessPlan());

            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            return new JsonResponse(array(
                'code' => 201,
                'data' => $serializer->serialize($charge, 'json'),
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
        $charge = $em->getRepository('BPBundle:Charge')->find($id);

        //check permissions
        $this->denyAccessUnlessGranted(null, $charge->getBusinessPlan());

        $em->remove($charge);
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
                'data' => $serializer->serialize($bp->getCharges(), 'json'),
                'message' => 'OK',
                'errors' => array('errors' => array(''))), 200);
        } elseif ($request->getRequestFormat() === 'html') {
            return $this->render('BPBundle:Frontend:Charge/list.html.twig', [
                        'charges' => $bp->getCharges(),
                        'totalCharge' => $bp->getTotalCharge(),
                        'accountingNbPeriod' => $bp->getInformation()->getAccountingPeriod(),
                        'accountingDate' => $bp->getInformation()->getClosingDate(),
            ]);
        }
    }

    public function getEditFormAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $charge = $em->getRepository('BPBundle:Charge')->find($id);

        if ($charge) {
            //check permissions
            $this->denyAccessUnlessGranted(null, $charge->getBusinessPlan());


            $options = ['attr' => []];

            $isTaux = false;
            $isCustomLabel = false;
            if ($request->get('isTaux')) {
                $isTaux = $request->get('isTaux') === 'true';
                $options['attr']['isTaux'] = $isTaux;
            }
            if ($request->get('isCustomLabel')) {
                $isCustomLabel = $request->get('isCustomLabel') === 'true';
                $options['attr']['isCustomLabel'] = $isCustomLabel;
            }

            $form = $this->createForm(ChargeType::class, $charge, $options);

            $form->handleRequest($request);

            return $this->render('BPBundle:Frontend:Charge/edit.html.twig', [
                        'charge' => $charge,
                        'editForm' => $form->createView(),
                        'action' => 'update',
                        'idCharge' => $id,
                        'accountingNbPeriod' => $charge->getBusinessPlan()->getInformation()->getAccountingPeriod()
            ]);
        }
    }

    public function getAddFormAction(Request $request, $idBusinessPlan) {
        $em = $this->getDoctrine()->getManager();
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $charge = new Charge();
        $charge->setBusinessPlan($bp);

        foreach ($bp->getExercices() as $exercice) {
            $charge->generateOneInfoCharge($exercice);
        }

        $isTaux = false;
        $isCustomLabel = false;
        if ($request->get('isTaux')) {
            $isTaux = $request->get('isTaux') === 'true';
        }
        if ($request->get('isCustomLabel')) {
            $isCustomLabel = $request->get('isCustomLabel') === 'true';
        }

        $options = ['attr' => [
                'isTaux' => $isTaux,
                'isCustomLabel' => $isCustomLabel
            ]
        ];

        $form = $this->createForm(ChargeType::class, $charge, $options);

        $form->handleRequest($request);

        return $this->render('BPBundle:Frontend:Charge/edit.html.twig', [
                    'charge' => $charge,
                    'editForm' => $form->createView(),
                    'action' => 'create',
                    'idCharge' => 0,
                    'idBusinessPlan' => $charge->getBusinessPlan()->getId(),
                    'accountingNbPeriod' => $charge->getBusinessPlan()->getInformation()->getAccountingPeriod()
        ]);
    }

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

}
