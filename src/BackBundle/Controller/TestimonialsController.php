<?php

namespace BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackBundle\Entity\Testimonials;
use BackBundle\Form\TestimonialsType;

/**
 * Testimonials controller.
 *
 */
class TestimonialsController extends Controller
{
    /**
     * Lists all Testimonials entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $testimonials = $em->getRepository('BackBundle:Testimonials')->findAll();

        return $this->render('BackBundle:Testimonials:index.html.twig', array(
            'testimonials' => $testimonials,
        ));
    }

    /**
     * Creates a new Testimonials entity.
     *
     */
    public function newAction(Request $request)
    {
        $testimonial = new Testimonials();
        $form = $this->createForm(TestimonialsType::class, $testimonial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $testimonial -> upload();
            $em->persist($testimonial);
            $em->flush();

            return $this->redirectToRoute('back_testimonials_index');
        }

        return $this->render('BackBundle:Testimonials:new.html.twig', array(
            'testimonial' => $testimonial,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Testimonials entity.
     *
     */
    public function showAction(Testimonials $testimonial)
    {
        $deleteForm = $this->createDeleteForm($testimonial);

        return $this->render('BackBundle:Testimonials:show.html.twig', array(
            'testimonial' => $testimonial,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Testimonials entity.
     *
     */
    public function editAction(Request $request, Testimonials $testimonial)
    {
        $deleteForm = $this->createDeleteForm($testimonial);
        $editForm = $this->createForm(TestimonialsType::class, $testimonial);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $testimonial -> upload();
            $em->persist($testimonial);
            $em->flush();

            return $this->redirectToRoute('back_testimonials_index');
        }

        return $this->render('BackBundle:Testimonials:edit.html.twig', array(
            'testimonial' => $testimonial,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Testimonials entity.
     *
     */
    public function deleteAction(Request $request, Testimonials $testimonial)
    {
        $form = $this->createDeleteForm($testimonial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($testimonial);
            $em->flush();
        }

        return $this->redirectToRoute('back_testimonials_index');
    }

    /**
     * Creates a form to delete a Testimonials entity.
     *
     * @param Testimonials $testimonial The Testimonials entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Testimonials $testimonial)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_testimonials_delete', array('id' => $testimonial->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Remove an entity from the list
     * @param Request $request
     * @param Testimonials $testimonial
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletedAction(Request $request, Testimonials $testimonial)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($testimonial);
        $em->flush();

        return $this->redirectToRoute('back_testimonials_index');
    }
}
