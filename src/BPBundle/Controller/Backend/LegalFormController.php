<?php

namespace BPBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BPBundle\Entity\LegalForm;
use BPBundle\Form\LegalFormType;

/**
 * LegalForm controller.
 *
 */
class LegalFormController extends Controller
{
    /**
     * Lists all LegalForm entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $legalForms = $em->getRepository('BPBundle:LegalForm')->findAll();

        return $this->render('BPBundle:Backend:Legal_form/index.html.twig', array(
            'legalForms' => $legalForms,
        ));
    }

    /**
     * Creates a new LegalForm entity.
     *
     */
    public function newAction(Request $request)
    {
        $legalForm = new LegalForm();
        $form = $this->createForm('BPBundle\Form\LegalFormType', $legalForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($legalForm);
            $em->flush();

            return $this->redirectToRoute('back_legalform_show', array('id' => $legalForm->getId()));
        }

        return $this->render('BPBundle:Backend:Legal_form/new.html.twig', array(
            'legalForm' => $legalForm,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a LegalForm entity.
     *
     */
    public function showAction(LegalForm $legalForm)
    {
        $deleteForm = $this->createDeleteForm($legalForm);

        return $this->render('BPBundle:Backend:Legal_form/show.html.twig', array(
            'legalForm' => $legalForm,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing LegalForm entity.
     *
     */
    public function editAction(Request $request, LegalForm $legalForm)
    {
        $deleteForm = $this->createDeleteForm($legalForm);
        $editForm = $this->createForm('BPBundle\Form\LegalFormType', $legalForm);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($legalForm);
            $em->flush();

            return $this->redirectToRoute('back_legalform_edit', array('id' => $legalForm->getId()));
        }

        return $this->render('BPBundle:Backend:Legal_form/edit.html.twig', array(
            'legalForm' => $legalForm,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a LegalForm entity.
     *
     */
    public function deleteAction(Request $request, LegalForm $legalForm)
    {
        $form = $this->createDeleteForm($legalForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($legalForm);
            $em->flush();
        }

        return $this->redirectToRoute('back_legalform_index');
    }

    /**
     * Creates a form to delete a LegalForm entity.
     *
     * @param LegalForm $legalForm The LegalForm entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LegalForm $legalForm)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_legalform_delete', array('id' => $legalForm->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Remove an entity from the list
     * @param Request $request
     * @param LegalForm $legalForm
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletedAction(Request $request, LegalForm $legalForm)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($legalForm);
        $em->flush();

        return $this->redirectToRoute('back_legalform_index');
    }
}
