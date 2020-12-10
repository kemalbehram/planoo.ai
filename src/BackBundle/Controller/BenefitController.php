<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Benefit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Benefit controller.
 *
 */
class BenefitController extends Controller
{
    /**
     * Lists all benefit entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $benefits = $em->getRepository('BackBundle:Benefit')->findAll();

        return $this->render('BackBundle:Benefit:index.html.twig', array(
            'benefits' => $benefits,
        ));
    }

    /**
     * Creates a new benefit entity.
     *
     */
    public function newAction(Request $request)
    {
        $benefit = new Benefit();
        $form = $this->createForm('BackBundle\Form\BenefitType', $benefit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($benefit);
            $em->flush($benefit);

            return $this->redirectToRoute('back_benefit_index');
        }

        return $this->render('BackBundle:Benefit:new.html.twig', array(
            'benefit' => $benefit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a benefit entity.
     *
     */
    public function showAction(Benefit $benefit)
    {
        $deleteForm = $this->createDeleteForm($benefit);

        return $this->render('BackBundle:Benefit:show.html.twig', array(
            'benefit' => $benefit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing benefit entity.
     *
     */
    public function editAction(Request $request, Benefit $benefit)
    {
        $deleteForm = $this->createDeleteForm($benefit);
        $editForm = $this->createForm('BackBundle\Form\BenefitType', $benefit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('back_benefit_index');
        }

        return $this->render('BackBundle:Benefit:edit.html.twig', array(
            'benefit' => $benefit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a benefit entity.
     *
     */
    public function deleteAction(Request $request, Benefit $benefit)
    {
        $form = $this->createDeleteForm($benefit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($benefit);
            $em->flush($benefit);
        }

        return $this->redirectToRoute('back_benefit_index');
    }

    /**
     * Creates a form to delete a benefit entity.
     *
     * @param Benefit $benefit The benefit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Benefit $benefit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_benefit_delete', array('id' => $benefit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
