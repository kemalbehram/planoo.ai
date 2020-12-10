<?php

namespace BPBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BPBundle\Entity\Country;
use BPBundle\Form\CountryType;

/**
 * Country controller.
 *
 */
class CountryController extends Controller
{
    /**
     * Lists all Country entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $countries = $em->getRepository('BPBundle:Country')->findAll();

        return $this->render('BPBundle:Backend:Country/index.html.twig', array(
            'countries' => $countries,
        ));
    }

    /**
     * Creates a new Country entity.
     *
     */
    public function newAction(Request $request)
    {
        $country = new Country();
        $form = $this->createForm('BPBundle\Form\CountryType', $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $country->upload();
            $em->persist($country);
            $em->flush();

            return $this->redirectToRoute('back_country_index');
        }

        return $this->render('BPBundle:Backend:Country/new.html.twig', array(
            'country' => $country,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Country entity.
     *
     */
    public function showAction(Country $country)
    {
        $deleteForm = $this->createDeleteForm($country);

        return $this->render('BPBundle:Backend:Country/show.html.twig', array(
            'country' => $country,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Country entity.
     *
     */
    public function editAction(Request $request, Country $country)
    {
        $deleteForm = $this->createDeleteForm($country);
        $editForm = $this->createForm('BPBundle\Form\CountryType', $country);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $country->upload();
            $em->persist($country);
            $em->flush();

            return $this->redirectToRoute('back_country_index');
        }

        return $this->render('BPBundle:Backend:Country/edit.html.twig', array(
            'country' => $country,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Country entity.
     *
     */
    public function deleteAction(Request $request, Country $country)
    {
        $form = $this->createDeleteForm($country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($country);
            $em->flush();
        }

        return $this->redirectToRoute('back_country_index');
    }

    /**
     * Creates a form to delete a Country entity.
     *
     * @param Country $country The Country entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Country $country)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('back_country_delete', array('id' => $country->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Remove an entity from the list
     * @param Request $request
     * @param Country $country
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletedAction(Request $request, Country $country)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($country);
        $em->flush();

        return $this->redirectToRoute('back_country_index');
    }
}
