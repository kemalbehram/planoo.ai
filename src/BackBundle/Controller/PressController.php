<?php

namespace BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackBundle\Entity\Press;
use BackBundle\Form\PressType;

/**
 * Press controller.
 *
 */
class PressController extends Controller
{
    /**
     * Lists all Press entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $presses = $em->getRepository('BackBundle:Press')->findAll();

        return $this->render('BackBundle:Press:index.html.twig', array(
            'presses' => $presses,
        ));
    }

    /**
     * Creates a new Press entity.
     *
     */
    public function newAction(Request $request)
    {
        $press = new Press();
        $form = $this->createForm(PressType::class, $press);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $press->upload();
            $em->persist($press);
            $em->flush();

            return $this->redirectToRoute('back_press_index');
        }

        return $this->render('BackBundle:Press:new.html.twig', array(
            'press' => $press,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Press entity.
     *
     */
    public function showAction(Press $press)
    {
        $deleteForm = $this->createDeleteForm($press);

        return $this->render('BackBundle:Press:show.html.twig', array(
            'press' => $press,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Press entity.
     *
     */
    public function editAction(Request $request, Press $press)
    {
        $deleteForm = $this->createDeleteForm($press);
        $editForm = $this->createForm(PressType::class, $press);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $press->upload();
            $em->persist($press);
            $em->flush();

            return $this->redirectToRoute('back_press_index');
        }

        return $this->render('BackBundle:Press:edit.html.twig', array(
            'press' => $press,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Press entity.
     *
     */
    public function deleteAction(Request $request, Press $press)
    {
        $form = $this->createDeleteForm($press);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($press);
            $em->flush();
        }

        return $this->redirectToRoute('back_press_index');
    }

    /**
     * Creates a form to delete a Press entity.
     *
     * @param Press $press The Press entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Press $press)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_press_delete', array('id' => $press->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Remove an entity from the list
     * @param Request $request
     * @param Press $press
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletedAction(Request $request, Press $press)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($press);
        $em->flush();

        return $this->redirectToRoute('back_press_index');
    }
}
