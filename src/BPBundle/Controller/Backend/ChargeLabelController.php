<?php

namespace BPBundle\Controller\Backend;

use BPBundle\Entity\ChargeLabel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Chargelabel controller.
 *
 */
class ChargeLabelController extends Controller
{
    /**
     * Lists all chargeLabel entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $findChargeLabels = $em->getRepository('BPBundle:ChargeLabel')->findAll();
        $chargeLabels = $this->get('knp_paginator')->paginate($findChargeLabels, $request->query->get('page', 1)/*page number*/, 10/*limit per page*/);

        return $this->render('BPBundle:Backend:Chargelabel/index.html.twig', array(
            'chargeLabels' => $chargeLabels,
        ));
    }

    /**
     * Creates a new chargeLabel entity.
     *
     */
    public function newAction(Request $request)
    {
        $chargeLabel = new Chargelabel();
        $form = $this->createForm('BPBundle\Form\ChargeLabelType', $chargeLabel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($chargeLabel);
            $em->flush($chargeLabel);

            return $this->redirectToRoute('back_charge_label_index');
        }

        return $this->render('BPBundle:Backend:Chargelabel/new.html.twig', array(
            'chargeLabel' => $chargeLabel,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a chargeLabel entity.
     *
     */
    public function showAction(ChargeLabel $chargeLabel)
    {
        $deleteForm = $this->createDeleteForm($chargeLabel);

        return $this->render('BPBundle:Backend:Chargelabel/show.html.twig', array(
            'chargeLabel' => $chargeLabel,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing chargeLabel entity.
     *
     */
    public function editAction(Request $request, ChargeLabel $chargeLabel)
    {
        $deleteForm = $this->createDeleteForm($chargeLabel);
        $editForm = $this->createForm('BPBundle\Form\ChargeLabelType', $chargeLabel);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('back_charge_label_index');
        }

        return $this->render('BPBundle:Backend:Chargelabel/edit.html.twig', array(
            'chargeLabel' => $chargeLabel,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a chargeLabel entity.
     *
     */
    public function deleteAction(Request $request, ChargeLabel $chargeLabel)
    {
        $form = $this->createDeleteForm($chargeLabel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($chargeLabel);
            $em->flush($chargeLabel);
        }

        return $this->redirectToRoute('back_charge_label_index');
    }

    /**
     * Creates a form to delete a chargeLabel entity.
     *
     * @param ChargeLabel $chargeLabel The chargeLabel entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ChargeLabel $chargeLabel)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_charge_label_delete', array('id' => $chargeLabel->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * Remove an entity from the list
     * @param Request $request
     * @param ChargeLabel $chargeLabel
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletedAction(Request $request, ChargeLabel $chargeLabel)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($chargeLabel);
        $em->flush();

        return $this->redirectToRoute('back_charge_label_index');
    }
}
