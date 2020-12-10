<?php

namespace BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\SocialCharge;
use UserBundle\Form\SocialChargeType;

/**
 * SocialCharge controller.
 *
 */
class SocialChargeController extends Controller {

    /**
     * Lists all SocialCharge entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $socialCharges = $em->getRepository('UserBundle:SocialCharge')->findAll();

        return $this->render('BackBundle:SocialCharge:index.html.twig', array(
                    'socialCharges' => $socialCharges,
        ));
    }

    /**
     * Creates a new SocialCharge entity.
     *
     */
    public function newAction(Request $request) {
        $socialCharge = new SocialCharge();
        $form = $this->createForm('UserBundle\Form\SocialChargeType', $socialCharge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($socialCharge);
            $em->flush();

            return $this->redirectToRoute('back_socialcharge_show', array('id' => $socialCharge->getId()));
        }

        return $this->render('BackBundle:SocialCharge:new.html.twig', array(
                    'socialCharge' => $socialCharge,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a SocialCharge entity.
     *
     */
    public function showAction(SocialCharge $socialCharge) {
        $deleteForm = $this->createDeleteForm($socialCharge);

        return $this->render('BackBundle:SocialCharge:show.html.twig', array(
                    'socialCharge' => $socialCharge,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing SocialCharge entity.
     *
     */
    public function editAction(Request $request, SocialCharge $socialCharge) {
        $deleteForm = $this->createDeleteForm($socialCharge);
        $editForm = $this->createForm('UserBundle\Form\SocialChargeType', $socialCharge);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($socialCharge);
            $em->flush();

            return $this->redirectToRoute('back_socialcharge_edit', array('id' => $socialCharge->getId()));
        }

        return $this->render('BackBundle:SocialCharge:edit.html.twig', array(
                    'socialCharge' => $socialCharge,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a SocialCharge entity.
     *
     */
    public function deleteAction(Request $request, SocialCharge $socialCharge) {
        $form = $this->createDeleteForm($socialCharge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($socialCharge);
            $em->flush();
        }

        return $this->redirectToRoute('back_socialcharge_index');
    }

    /**
     * Creates a form to delete a SocialCharge entity.
     *
     * @param SocialCharge $socialCharge The SocialCharge entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SocialCharge $socialCharge) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('back_socialcharge_delete', array('id' => $socialCharge->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * Remove an entity from the list
     * @param Request $request
     * @param SocialCharge $socialCharge
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletedAction(Request $request, SocialCharge $socialCharge) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($socialCharge);
        $em->flush();

        return $this->redirectToRoute('promotion_admin_coupon_index', array('isAdminView' => true));
    }

}
