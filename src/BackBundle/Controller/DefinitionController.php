<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Definition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Definition controller.
 *
 */
class DefinitionController extends Controller
{
    /**
     * Lists all definition entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $definitions = $em->getRepository('BackBundle:Definition')->findAll();

        return $this->render('BackBundle:Definition:index.html.twig', array(
            'definitions' => $definitions,
        ));
    }

    /**
     * Creates a new definition entity.
     *
     */
    public function newAction(Request $request)
    {
        $definition = new Definition();
        $form = $this->createForm('BackBundle\Form\DefinitionType', $definition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $definition->upload();
            $em->persist($definition);
            $em->flush();

            return $this->redirectToRoute('back_concept_index');
        }

        return $this->render('BackBundle:Definition:new.html.twig', array(
            'definition' => $definition,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a definition entity.
     *
     */
    public function showAction(Definition $definition)
    {
        $deleteForm = $this->createDeleteForm($definition);

        return $this->render('BackBundle:Definition:show.html.twig', array(
            'definition' => $definition,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing definition entity.
     *
     */
    public function editAction(Request $request, Definition $definition)
    {
        $deleteForm = $this->createDeleteForm($definition);
        $editForm = $this->createForm('BackBundle\Form\DefinitionType', $definition);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $definition -> upload();
            $em->persist($definition);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('back_concept_index');
        }

        return $this->render('BackBundle:Definition:edit.html.twig', array(
            'definition' => $definition,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a definition entity.
     *
     */
    public function deleteAction(Request $request, Definition $definition)
    {
        $form = $this->createDeleteForm($definition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($definition);
            $em->flush();
        }

        return $this->redirectToRoute('back_concept_index');
    }

    /**
     * Creates a form to delete a definition entity.
     *
     * @param Definition $definition The definition entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Definition $definition)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_definition_delete', array('id' => $definition->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * Remove an entity from the list
     * @param Request $request
     * @param Definition $definition
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletedAction(Request $request, Definition $definition)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($definition);
        $em->flush();

        return $this->redirectToRoute('back_concept_index');
    }
}
