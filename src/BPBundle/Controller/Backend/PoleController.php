<?php

namespace BPBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BPBundle\Entity\Pole;
use BPBundle\Form\PoleType;

/**
 * Pole controller.
 *
 */
class PoleController extends Controller
{
    /**
     * Lists all Pole entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $poles = $em->getRepository('BPBundle:Pole')->findAll();

        return $this->render('BPBundle:Backend:Staff/Pole/index.html.twig', array(
            'poles' => $poles,
        ));
    }

    /**
     * Creates a new Pole entity.
     *
     */
    public function newAction(Request $request)
    {
        $pole = new Pole();
        $form = $this->createForm('BPBundle\Form\PoleType', $pole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pole);
            $em->flush();

            return $this->redirectToRoute('back_staff');
        }

        return $this->render('BPBundle:Backend:Staff/Pole/new.html.twig', array(
            'pole' => $pole,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Pole entity.
     *
     */
    public function showAction(Pole $pole)
    {
        $deleteForm = $this->createDeleteForm($pole);

        return $this->render('BPBundle:Backend:Staff/Pole/show.html.twig', array(
            'pole' => $pole,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Pole entity.
     *
     */
    public function editAction(Request $request, Pole $pole)
    {
        $deleteForm = $this->createDeleteForm($pole);
        $editForm = $this->createForm('BPBundle\Form\PoleType', $pole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pole);
            $em->flush();

            return $this->redirectToRoute('back_staff');
        }

        return $this->render('BPBundle:Backend:Staff/Pole/edit.html.twig', array(
            'pole' => $pole,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Pole entity.
     *
     */
    public function deleteAction(Request $request, Pole $pole)
    {
        $form = $this->createDeleteForm($pole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pole);
            $em->flush();
        }

//        die(dump($pole));
        return $this->redirectToRoute('back_staff');
    }

    /**
     * Creates a form to delete a Pole entity.
     *
     * @param Pole $pole The Pole entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Pole $pole)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_pole_delete', array('id' => $pole->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Remove an entity from the list
     * @param Request $request
     * @param Pole $pole
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletedAction(Request $request, Pole $pole)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pole);
            $em->flush();

        return $this->redirectToRoute('back_staff');
    }
}
