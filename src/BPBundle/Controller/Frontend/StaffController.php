<?php

/**
 * Created by PhpStorm.
 * User: wac28
 * Date: 26/09/16
 * Time: 11:36
 */

namespace BPBundle\Controller\Frontend;

use BPBundle\Form\StaffType;
use BPBundle\Entity\Staff;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class StaffController extends Controller {

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

    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $staff = $em->getRepository('BPBundle:Staff')->find($id);

        //check permissions
        $this->denyAccessUnlessGranted(null, $staff->getBusinessPlan());

        $form = $this->createForm(StaffType::class, $staff);

        $form->submit($request->request->get($form->getName()));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($staff);
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

        $staff = new Staff();
        $staff->setBusinessPlan($bp);

        $form = $this->createForm(StaffType::class, $staff);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($staff);
            $em->flush();

            return new JsonResponse(array(
                'code' => 201,
                'id' => $staff->getId(),
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
        $staff = $em->getRepository('BPBundle:Staff')->find($id);

        if ($staff) {
//check permissions
            $this->denyAccessUnlessGranted(null, $staff->getBusinessPlan());

            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            return new JsonResponse(array(
                'code' => 201,
                'data' => $serializer->serialize($staff, 'json'),
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
        $staff = $em->getRepository('BPBundle:Staff')->find($id);

//check permissions
        $this->denyAccessUnlessGranted(null, $staff->getBusinessPlan());

        $em->remove($staff);
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
                'data' => $serializer->serialize($bp->getStaffs(), 'json'),
                'message' => 'OK',
                'errors' => array('errors' => array(''))), 200);
        } elseif ($request->getRequestFormat() === 'html') {
            return $this->render('BPBundle:Frontend:Staff/list.html.twig', [
                        'staffs' => $bp->getStaffs(),
                        'accountingNbPeriod' => $bp->getInformation()->getAccountingPeriod(),
                        'accountingDate' => $bp->getInformation()->getClosingDate(),
            ]);
        }
    }

    public function getEditFormAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $staff = $em->getRepository('BPBundle:Staff')->find($id);

        if ($staff) {
            //check permissions
            $this->denyAccessUnlessGranted(null, $staff->getBusinessPlan());

            $form = $this->createForm(StaffType::class, $staff);

            $form->handleRequest($request);

            return $this->render('BPBundle:Frontend:Staff/edit.html.twig', [
                        'staff' => $staff,
                        'editForm' => $form->createView(),
                        'action' => 'update',
                        'idStaff' => $id
            ]);
        }
    }

    public function getAddFormAction(Request $request, $idBusinessPlan) {
        $em = $this->getDoctrine()->getManager();
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $staff = new Staff();
        $staff->setBusinessPlan($bp);

        $form = $this->createForm(StaffType::class, $staff);

        $form->handleRequest($request);

        return $this->render('BPBundle:Frontend:Staff/edit.html.twig', [
                    'staff' => $staff,
                    'editForm' => $form->createView(),
                    'action' => 'create',
                    'idStaff' => 0,
                    'idBusinessPlan' => $staff->getBusinessPlan()->getId(),
                    'accountingNbPeriod' => $staff->getBusinessPlan()->getInformation()->getAccountingPeriod()
        ]);
    }

}
