<?php

/**
 * Created by PhpStorm.
 * User: wac28
 * Date: 26/09/16
 * Time: 11:36
 */

namespace BPBundle\Controller\Frontend;

use BPBundle\Form\InvestissementType;
use BPBundle\Entity\Investissement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class InvestissementController extends Controller {

    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $invest = $em->getRepository('BPBundle:Investissement')->find($id);

        //check permissions
        $this->denyAccessUnlessGranted(null, $invest->getBusinessPlan());

        $form = $this->createForm(InvestissementType::class, $invest);

        $form->submit($request->request->get($form->getName()));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($invest);
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

        // Création d'une nouvelle source de revenu
        $invest = new Investissement();
        $invest->setBusinessPlan($bp);

        $form = $this->createForm(InvestissementType::class, $invest);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($invest);
            $em->flush();

            return new JsonResponse(array(
                'code' => 201,
                'id' => $invest->getId(),
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
        $invest = $em->getRepository('BPBundle:Investissement')->find($id);

        if ($invest) {
            //check permissions
            $this->denyAccessUnlessGranted(null, $invest->getBusinessPlan());

            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            return new JsonResponse(array(
                'code' => 201,
                'data' => $serializer->serialize($invest, 'json'),
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
        $invest = $em->getRepository('BPBundle:Investissement')->find($id);

        //check permissions
        $this->denyAccessUnlessGranted(null, $invest->getBusinessPlan());

        $em->remove($invest);
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
                'data' => $serializer->serialize($bp->getInvestissements(), 'json'),
                'message' => 'OK',
                'errors' => array('errors' => array(''))), 200);
        } elseif ($request->getRequestFormat() === 'html') {
            return $this->render('BPBundle:Frontend:Investissement/list.html.twig', [
                        'invests' => $bp->getInvestissements(),
            ]);
        }
    }

    public function getEditFormAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $invest = $em->getRepository('BPBundle:Investissement')->find($id);

        if ($invest) {
            //check permissions
            $this->denyAccessUnlessGranted(null, $invest->getBusinessPlan());

            $form = $this->createForm(InvestissementType::class, $invest);

            $form->handleRequest($request);

            return $this->render('BPBundle:Frontend:Investissement/edit.html.twig', [
                        'invest' => $invest,
                        'editForm' => $form->createView(),
                        'action' => 'update',
                        'idInvest' => $id
            ]);
        }
    }

    public function getAddFormAction(Request $request, $idBusinessPlan) {
        $em = $this->getDoctrine()->getManager();
        $bp = $em->getRepository('BPBundle:BusinessPlan')->find($idBusinessPlan);

        //check permissions
        $this->denyAccessUnlessGranted(null, $bp);

        $invest = new Investissement();
        $invest->setBusinessPlan($bp);

        $form = $this->createForm(InvestissementType::class, $invest);

        $form->handleRequest($request);

        return $this->render('BPBundle:Frontend:Investissement/edit.html.twig', [
                    'investissement' => $invest,
                    'editForm' => $form->createView(),
                    'action' => 'create',
                    'idInvest' => 0,
                    'idBusinessPlan' => $invest->getBusinessPlan()->getId()
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
