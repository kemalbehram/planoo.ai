<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Concept;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Concept controller.
 *
 */
class ConceptController extends Controller
{
    /**
     * Lists all concept entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $concepts = $em->getRepository('BackBundle:Concept')->findAll();

        return $this->render('BackBundle:Concept:index.html.twig', array(
            'concepts' => $concepts,
        ));
    }

    /**
     * Creates a new concept entity.
     *
     */
    public function newAction(Request $request)
    {
        $concept = new Concept();
        $form = $this->createForm('BackBundle\Form\ConceptType', $concept);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($concept);
            $em->flush();

            return $this->redirectToRoute('back_concept_index');
        }

        return $this->render('BackBundle:Concept:new.html.twig', array(
            'concept' => $concept,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a concept entity.
     *
     */
    public function showAction(Concept $concept)
    {
        $deleteForm = $this->createDeleteForm($concept);

        return $this->render('BackBundle:Concept:show.html.twig', array(
            'concept' => $concept,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing concept entity.
     *
     */
    public function editAction(Request $request, Concept $concept)
    {
        $deleteForm = $this->createDeleteForm($concept);
        $editForm = $this->createForm('BackBundle\Form\ConceptType', $concept);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('back_concept_index');
        }

        return $this->render('BackBundle:Concept:edit.html.twig', array(
            'concept' => $concept,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a concept entity.
     *
     */
    public function deleteAction(Request $request, Concept $concept)
    {
        $form = $this->createDeleteForm($concept);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($concept);
            $em->flush();
        }

        return $this->redirectToRoute('back_concept_index');
    }

    /**
     * Creates a form to delete a concept entity.
     *
     * @param Concept $concept The concept entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Concept $concept)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_concept_delete', array('id' => $concept->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Remove an entity from the list
     * @param Request $request
     * @param Concept $concept
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletedAction(Request $request, Concept $concept)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($concept);
        $em->flush();

        return $this->redirectToRoute('back_concept_index');
    }
}
